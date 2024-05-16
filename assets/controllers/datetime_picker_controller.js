import { Controller } from "stimulus";
import flatpickr from "flatpickr";
import confirmDatePlugin from "flatpickr/dist/plugins/confirmDate/confirmDate";
import { French } from "flatpickr/dist/l10n/fr.js";

export default class extends Controller {
    connect() {
        flatpickr(this.element, {
            locale: French,
            dateFormat: "Y-m-d H:i",
            enableTime: true,
            time_24hr: true,
            plugins: [new confirmDatePlugin({})]
        });
    }
}
