$(document).ready( function() {

    var delCommBtn = $(".comment-del").map(function() {

            this.addEventListener('click', function(e) {
            
            var id      = this.getAttribute('data-id')
            var blogId  = this.getAttribute('data-blogId')
            commentDel(id, blogId)
        })

    }).get()

    var listCommBtn = $(".comment-list").map(function() {

        this.addEventListener('click', function(e) {
            var vis     = this.getAttribute('data-show')
            
            if (vis === 'hidden') {
                var id          = e.target.getAttribute('data-id')
                this.innerHTML  = '<i class="fa fa-times" aria-hidden="true"></i>'
                element_id      = 'commentList' + id
                this.setAttribute('data-show', 'show')

                cList(id, element_id)
            } else {
                this.setAttribute('data-show', 'hidden')
                this.innerHTML = '<i class="fa fa-list" aria-hidden="true"></i>'
                var id      = e.target.getAttribute('data-id')
                listUL = document.getElementById('commentList' + id)
                listUL.innerHTML = ''
            }
        })

    }).get()

    var addCommBtn = $(".comment-add").map(function() {
        
        this.addEventListener('click', function(e) {
            
            $('#newComment').modal('show')
            var id = e.target.getAttribute('data-id')
            $('#submitComment').attr('data-id', id)
            $('#commentBox').val('')
        })

    }).get()

    subCommBtn = $('#submitComment').on('click', function() {
        var id      = $('#submitComment').data('id')
        var text    = $('#commentBox').val()

        commentAdd(id, text)
    })

    function cList(id, element_id) {
        listUL = document.getElementById(element_id)
        listUL.innerHTML = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>'
        
        $.ajax({
            url     : Routing.generate('_comment_get_list'),
            method  : 'GET',
            async: false,
            cache: false,
            timeout: 30000,
            error: function(){
                listUL.innerHTML = 'out of time';
            },
            data    : { 
                id:     id, 
            },
        }).done((res) => {
            listUL.innerHTML = '';
            for (var k in res) {
                var listLI = document.createElement("li")
                listLI.classList.add("bg-white")
                listLI.classList.add("list-group-item")
                listUL.appendChild(listLI)

                var listLItext = document.createElement("small")
                listLI.classList.add("text-secondary")
                listLItext.innerHTML = res[k]
                listLI.appendChild(listLItext)
            }
        })

    }

    function commentAdd(id, text) {
        $.ajax({
            url     : Routing.generate('_comment_add'),
            method  : 'PUT',
            async: false,
            cache: false,
            timeout: 30000,
            error: function(){
                listUL.innerHTML = 'out of time';
            },
            data    : { 
                id:     id,
                text:   text,
            },
        }).done((res) => {
            $('#newComment').modal('toggle')
            listUL = document.getElementById('commentList' + id)
        listUL.innerHTML = 'new comment added..'
        })
    }

    function commentDel(id, blogId) {
        $.ajax({
            url     : Routing.generate('_comment_delete'),
            method  : 'DELETE',
            async: false,
            cache: false,
            timeout: 30000,
            error: function(){
                listUL.innerHTML = 'out of time';
            },
            data    : { 
                id:     id,
            },
        }).done((res) => {
            if (res == 'OK') {
                location.reload()
            } else {
                listUL = document.getElementById('viewCommentList')
                listUL.innerHTML = 'delete comment failed'
            }
        })
    }

    function debug(res) {
        for(var k in res ){
            console.log(k + " : " + res[k])
        }
    }
})