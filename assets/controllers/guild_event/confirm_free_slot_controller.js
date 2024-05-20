import {Controller} from '@hotwired/stimulus'
import toastr from "toastr"

export default class extends Controller {
    static values = {url: String}

    connect() {
        $(this.element).on('click', (event) => this.confirmDeletion(event))
    }

    confirmDeletion(event) {
        event.preventDefault()
        const url = this.urlValue
        const slotUsername = $(this.element).data('slot-user')
        const currentUsername = $(this.element).data('current-user')

        let message
        if (slotUsername === currentUsername) {
            message = 'Vous êtes sur le point de libérer votre slot. Êtes-vous sûr de vouloir continuer ?'
        } else {
            message = 'Êtes-vous sûr de vouloir libérer le slot appartenant à ' + slotUsername + ' ?'
        }

        $.confirm({
            icon: 'bi bi-exclamation-triangle-fill',
            theme: 'supervan',
            title: 'Libérer un slot',
            content: message,
            type: 'red',
            typeAnimated: true,
            buttons: {
                confirm: {
                    text: 'Confirmer',
                    action: () => {
                        $(this.element).addClass('d-none')
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
                                }, 2000)
                            }
                        })
                    }
                },
                cancel: {
                    text: 'Annuler'
                }
            }
        })
    }
}
