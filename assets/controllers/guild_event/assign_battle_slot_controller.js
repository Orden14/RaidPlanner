import { Controller  } from "stimulus"
import toastr from 'toastr'

export default class extends Controller {
    static values = {url: String}

    connect() {
        $(this.element).on('click', (event) => this.manageSlot(event))
    }

    manageSlot(event) {
        event.preventDefault()
        $(this.element).addClass('d-none')
        const url = this.urlValue

        toastr.options = {
            "timeOut": "4000",
        }

        $.ajax({
            url: url,
            method: 'GET',
            success: () => {
                this.reloadPage()
                toastr.success('Slot assigné')
            },
            error: () => {
                this.reloadPage()
                toastr.error('Vous ne pouvez pas effectuer cette action')
            }
        })
    }

    reloadPage () {
        $.ajax({
            method: 'GET',
            url: window.location.href,
            success: (html) => {
                $('#guildEventHelder').replaceWith($(html).find('#guildEventHelder'))
            }
        })
    }
}
