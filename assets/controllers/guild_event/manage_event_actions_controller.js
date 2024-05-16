import { Controller } from "stimulus"

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
                $(`[data-slot-assign-event-battle-id=${eventBattleId}]`).not(this.element).remove()

                this.element.parentElement.outerHTML = response
            },
            error: (error) => {
                console.error("Error assigning slot:", error)
            }
        })
    }
}
