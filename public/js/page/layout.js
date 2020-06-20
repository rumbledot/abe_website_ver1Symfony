const blogs = document.getElementsByClassName('delete-blog')

$(document).ready( function() {

    for(var i = 0; i < blogs.length; i = i + 1) {
    console.log(i)
    blogs[i].addEventListener("click", function (e) {
        console.log("Clicked index: " + i);
        if (confirm('Are you sure ?')) {
            const id = e.target.getAttribute('data-id')

            fetch(`/delete/${id}`, {method: 'DELETE'}).then(res => window.location.reload())
        }
    })
    }
})