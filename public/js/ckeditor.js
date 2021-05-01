// On initialise CKEditor
BalloonEditor
.create(
    document.querySelector("#editor"), {
        toolbar: ['heading','|', 'bold', 'italic', '|', 'link', '|', 'bulletedList', 'numberedList'],
        heading: {
            options: [{
                model: 'paragraph',
                title: 'Paragraphe',
                class: 'ck-heading_paragraph'
            },
            {
                model: 'heading1',
                title: 'Titre 1',
                class: 'ck-heading_heading1',
                view: 'h1'
            },
            {
                model: 'heading2',
                title: 'Titre 2',
                class: 'ck-heading_heading2',
                view: 'h2'
            }]
        }
    }
)
.then(editor => {
    editor.sourceElement.parentElement.addEventListener("submit", function(e){
        e.preventDefault();
        this.querySelector("#editor + input").value = editor.getData();
        this.submit();
    })
});