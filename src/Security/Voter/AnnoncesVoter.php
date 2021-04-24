<?php

namespace App\Security\Voter;

use App\Entity\Annonces;
use App\Entity\Users;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class AnnoncesVoter extends Voter
{
    const ANNONCE_EDIT = 'annonce_edit';
    const ANNONCE_DELETE = 'annonce_delete';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $annonce)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::ANNONCE_EDIT, self::ANNONCE_DELETE])
            && $annonce instanceof \App\Entity\Annonces;
    }

    protected function voteOnAttribute($attribute, $annonce, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // On vérifie si l'utilisateur est admin
        if($this->security->isGranted('ROLE_ADMIN')) return true;

        // On vérifie si l'annonce a un propriétaire
        if(null === $annonce->getUsers()) return false;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::ANNONCE_EDIT:
                // on vérifie si on peut éditer
                return $this->canEdit($annonce, $user);
                break;
            case self::ANNONCE_DELETE:
                // on vérifie si on peut supprimer
                return $this->canDelete();
                break;
        }

        return false;
    }

    private function canEdit(Annonces $annonce, Users $user){
        // Le propriétaire de l'annonce peut la modifier
        return $user === $annonce->getUsers();
    }

    private function canDelete(){
        // Le propriétaire de l'annonce peut la supprimer
        if($this->security->isGranted('ROLE_EDITOR')) return true;
        return false;
    }

}
