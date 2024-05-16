import { Controller } from "stimulus"
import toastr from 'toastr'

export default class extends Controller {
    static values = { url: String }

    connect() {
        this.element.addEventListener('click', (event) => this.manageSlot(event))
    }

    manageSlot(event) {
        event.preventDefault()
        const url = this.urlValue

        $.ajax({
            url: url,
            method: 'GET',
            success: (response) => {
                const eventBattleId = $(this.element).data('slot-assign-event-battle-id')
                $(`[data-slot-assign-event-battle-id=${eventBattleId}]`).not(this.element).addClass('d-none')

                $(this.element).parent().replaceWith(response);
            },
            error: () => {
                toastr.error('Erreur lors de la modification du slot', 'Erreur')
            }
        })
    }
}
