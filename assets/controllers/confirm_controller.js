import {Controller} from '@hotwired/stimulus'

export default class extends Controller {
    confirmDeletion(event) {
        event.preventDefault()
        $.confirm({
            icon: 'bi bi-exclamation-triangle-fill',
            theme: 'supervan',
            title: 'Attention !',
            content: 'Êtes-vous sûr de vouloir procéder à la suppression ?',
            type: 'red',
            typeAnimated: true,
            buttons: {
                confirm: {
                    text: 'Confirmer',
                    action: () => {
                        $(this.element).trigger('submit')
                    }
                },
                cancel: {
                    text: 'Annuler'
                }
            }
        })
    }
}
