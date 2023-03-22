import axios from "axios";
import {mapDataToFormData} from "../../helpers/global";
import {LocalizedAcColumnSettings} from "../../types/admin-columns";

declare const ajaxurl: string;
declare const AC: LocalizedAcColumnSettings;

export default class InfoScreenOption {

    constructor(private name: string, private input: HTMLInputElement, private toggleClass: string, private container: HTMLElement, private nonce: string) {
        this.initEvents();
    }

    initEvents() {
        this.input.addEventListener('change', () => {
            this.input.checked
                ? this.container.classList.add(this.toggleClass)
                : this.container.classList.remove(this.toggleClass)

            this.persist();
        });
    }

    persist(){
        axios.post( ajaxurl, mapDataToFormData({
            action: 'ac_admin_screen_options',
            _ajax_nonce: this.nonce,
            //_ajax_nonce: AC._ajax_nonce,
            option_name: this.name,
            option_value: this.input.checked ? 1 : 0
        }))
    }
}