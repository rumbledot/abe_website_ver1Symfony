$(document).ready (() => {
    
    
})

function curl(url, method, data) {
    var res = $.ajax({
                url     : Routing.generate(url),
                method  : method,
                async   : false,
                cache   : false,
                timeout : 30000,
                data    : data,
                error   : function() {
                            return "Takes too long!"
                            }
        })
    .responseText

    return res
}

function curl1(url, method, data) {
    var res = $.ajax({
                url     : Routing.generate(url),
                method  : method,
                async   : false,
                cache   : false,
                timeout : 30000,
                processData: false,
                data    : data,
                error   : function() {
                            return "Takes too long!"
                            }
        })
    .responseText

    return res
}

function renderList(lists) {

    var li = ''

    for (var i = 0; i < lists.length; i++) {
        li +=   '<li class="list-group-item bg-secondary">' + lists[i]
        li +=   '<button class="btn btn-secondary text-danger btn-sm rounded-circle float-right elementListRemoveList" data-id="' + i + '">'
        li +=   '<i class="fas fa-times"></i>'
        li +=   '</button></li>'
    }

    return li
}

function newFootnote() {
    const popUp = $('#newElementModal')
    popUp.modal('toggle')

    const addBtn = $('#btn-confirm')
    const cancelBtn = $('#btn-cancel-modal')

    const elDiv = $('#elements')
    const popUpDiv = $('#newElementModalBody')

    var r = curl('_generate_modal', 'GET', { type: 'footnote' })
    if (r) {
        var res = JSON.parse(r)
        popUpDiv.html(res['body'])
    }

    addBtn.on('click', function() {
        const footnote =  $('#footnoteBody').val()

        if (footnote.length > 0) {
            var r = curl('_footnote_new', 'PUT', { data: footnote })
            if (r) {
                var res = JSON.parse(r)
                elDiv.html(res['body'])
            }
        }
        popUp.modal('toggle')
    })

    cancelBtn.on('click', function() {
        popUpDiv.html('')
        popUp.modal('toggle')
    })
}

function newList() {
    const popUp = $('#newElementModal')
    popUp.modal('toggle')

    const addBtn        = $('#btn-confirm')
    const cancelBtn     = $('#btn-cancel-modal')

    const elDiv     = $('#elements')
    const popUpDiv  = $('#newElementModalBody')

    var index       = 0
    var lists       = []
    var checkout    = true

    var r = curl('_generate_modal', 'GET', { type: 'list' })
    if (r) {
        var res = JSON.parse(r)
        popUpDiv.html(res['body'])
        
        const addListBtn    = $('#elementListAddList')
        const listInputs    = $("#listItems")
        var listDiv         = $('#elementListOfItems')
        
        addListBtn.on('click', function() {
            checkout = true
            index = lists.length

            if (listInputs.val() == '') {
                    $(this).attr("placeholder", "Task cannot be empty!");
                    checkout = false
            }
    
            if (checkout == true && index < 5) {
                lists.push(listInputs.val())
                listInputs.val('')

                listDiv.html(renderList(lists))

                var delCommBtn = $(".elementListRemoveList").map(function() {
                    this.addEventListener('click', function(e) {
                        var id      = parseInt(this.getAttribute('data-id'))
                        lists.splice(id, 1)

                        listDiv.html('')
                        listDiv.html(renderList(lists))
                    })
                }).get()
            } else {
                $('.alert').show()
            }
        })
    }

    addBtn.on('click', function() {
        if (checkout && (lists.length > 0)) {
            var r = curl('_list_new', 'PUT', { data: lists })
            if (r) {
                var res = JSON.parse(r)
                elDiv.html(res['body'])
            }
            popUp.modal('toggle')
        } else {
            alert('Task cannot be empty!')
        }
    })

    cancelBtn.on('click', function() {
        popUpDiv.html('')
        popUp.modal('toggle')
    })
}

function newPicture() {
    const popUp = $('#newElementModal')
    popUp.modal('toggle')

    const addBtn        = $('#btn-confirm')
    const cancelBtn     = $('#btn-cancel-modal')

    const elDiv     = $('#elements')
    const popUpDiv  = $('#newElementModalBody')

    var checkout    = false

    var r = curl('_generate_modal', 'GET', { type: 'picture' })
    if (r) {
        var res = JSON.parse(r)
        popUpDiv.html(res['body'])

        const imgPrev   = $('#previewImageFile')

        const imgInput  = $('#inputImageFile')
        const imgTitle  = $('#imageTitle')
        const imgDesc   = $('#imageDesc')

        var reader      = new FileReader()
        var formData    = new FormData()
        var blobPic

        imgInput.change(function() {
            if (imgInput[0].files && imgInput[0].files[0]) {
                
                reader.onload = function(e) {
                    imgPrev.attr('src', e.target.result)
                    blobPic  = e.target.result
                    checkout = true
                }
                reader.readAsDataURL(imgInput[0].files[0])
            }
        })

        addBtn.on('click', function() {
            if (checkout && imgTitle.val() != '' && imgDesc.val() != '') {
                formData.append('pic', blobPic)
                formData.append('title', imgTitle.val())
                formData.append('desc', imgDesc.val())
                formData.append('filename', imgInput.val().slice(12))

                var r = curl('_picture_new', 'POST', { data: 'fake', id: 'this is it!' })
                if (r) {
                    var res = JSON.parse(r)
                    elDiv.html(res['body'])
                }
                // console.log(imgInput.val().slice(12))
                // console.log(imgTitle.val())
                // console.log(imgDesc.val())
                // console.log(blobPic)
                popUp.modal('toggle')
            } else {
                alert('Picture cannot be empty!')
            }
        })
    }


    cancelBtn.on('click', function() {
        popUpDiv.html('')
        popUp.modal('toggle')
    })
}