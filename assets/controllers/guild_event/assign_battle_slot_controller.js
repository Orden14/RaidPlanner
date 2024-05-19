import {Controller} from "stimulus"
import toastr from 'toastr'

export default class extends Controller {
    static values = {url: String}

    connect() {
        $(this.element).on('click', (event) => this.manageSlot(event));
    }

    manageSlot(event) {
        event.preventDefault()
        const url = this.urlValue

        $.ajax({
            url: url,
            method: 'GET',
            success: () => {
                location.reload()
            },
            error: (jqXHR, textStatus, errorThrown) => {
                toastr.options = {
                    "timeOut": "4000",
                }
                toastr.error(jqXHR.responseText || errorThrown, textStatus)

                setTimeout(() => {
                    location.reload()
                }, 2000);
            }
        })
    }
}
