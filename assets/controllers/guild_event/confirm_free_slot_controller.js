import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    connect() {
        this.element.addEventListener('click', (event) => this.confirmDeletion(event))
    }

    confirmDeletion (event) {
        event.preventDefault()
        const targetUrl = this.element.getAttribute('href')
        const slotUsername = this.element.dataset.slotUser;
        const currentUsername = this.element.dataset.currentUser;

        let message;
        if (slotUsername === currentUsername) {
            message = 'Vous êtes sur le point de libérer votre slot. Êtes-vous sûr de vouloir continuer ?';
        } else {
            message = 'Êtes-vous sûr de vouloir libérer le slot appartenant à ' + slotUsername + ' ?';
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
                        window.location.href = targetUrl
                    }
                },
                cancel: {
                    text: 'Annuler'
                }
            }
        })
    }
}
