import { Controller } from 'stimulus'

export default class extends Controller {
    connect() {
        this.passwordRequirements()
    }

    passwordRequirements() {
        let options = {
            numCharacters: 10,
            useLowercase: true,
            useUppercase: true,
            useNumbers: true,
            infoMessage: '',
            style: "dark",
            fadeTime:300
        }

        options.infoMessage = 'La taille minimum du mode de passe doit être de ' + options.numCharacters + ' caractères. Il doit contenir au moins 1 lowercase minuscule, 1 majuscule, et 1 chiffre.'

        let numCharactersUI = '<li class="pr-numCharacters"><span></span>10 caractères</li>',
            useLowercaseUI = '',
            useUppercaseUI = '',
            useNumbersUI   = ''

        if (options.useLowercase === true) {
            useLowercaseUI = '<li class="pr-useLowercase"><span></span>Minuscule</li>'
        }
        if (options.useUppercase === true) {
            useUppercaseUI = '<li class="pr-useUppercase"><span></span>Majuscule</li>'
        }
        if (options.useNumbers === true) {
            useNumbersUI = '<li class="pr-useNumbers"><span></span>Chiffre</li>'
        }

        let messageDiv = '<div id="pr-box"><i></i><div id="pr-box-inner"><p>' + options.infoMessage + '</p><ul>' + numCharactersUI + useLowercaseUI + useUppercaseUI + useNumbersUI + '</ul></div></div>'

        let numCharactersDone = true,
            useLowercaseDone = true,
            useUppercaseDone = true,
            useNumbersDone   = true

        let showMessage = () => {
            const prBoxElement = $("#pr-box")

            if (numCharactersDone === false || useLowercaseDone === false || useUppercaseDone === false || useNumbersDone === false) {
                if (prBoxElement.length === 0) {
                    $(this.element).parent().append(messageDiv)
                }
                prBoxElement.addClass(options.style).fadeIn(options.fadeTime)
            }
        }

        let deleteMessage = () => {
            let targetMessage = $("#pr-box")
            targetMessage.fadeOut(options.fadeTime, function(){
                $(this).remove()
            })
        }

        let checkCompleted = () => {
            if (numCharactersDone === true && useLowercaseDone === true && useUppercaseDone === true && useNumbersDone === true) {
                deleteMessage()
            } else {
                showMessage()
            }
        }

        $(this.element).on("focus", () => {
            showMessage()
        })

        $(this.element).on("blur", () => {
            deleteMessage()
        })

        let lowerCase = /[a-z]/,
            upperCase = /[A-Z]/,
            numbers = /\d/

        $(this.element).on("keyup focus", function (){
            let thisVal = $(this).val()

            checkCompleted()

            if ( thisVal.length >= options.numCharacters ) {
                $(".pr-numCharacters span").addClass("pr-ok")
                numCharactersDone = true
            } else {
                $(".pr-numCharacters span").removeClass("pr-ok")
                numCharactersDone = false
            }

            if (options.useLowercase === true) {
                if ( thisVal.match(lowerCase) ) {
                    $(".pr-useLowercase span").addClass("pr-ok")
                    useLowercaseDone = true
                } else {
                    $(".pr-useLowercase span").removeClass("pr-ok")
                    useLowercaseDone = false
                }
            }

            if (options.useUppercase === true) {
                if ( thisVal.match(upperCase) ) {
                    $(".pr-useUppercase span").addClass("pr-ok")
                    useUppercaseDone = true
                } else {
                    $(".pr-useUppercase span").removeClass("pr-ok")
                    useUppercaseDone = false
                }
            }

            if (options.useNumbers === true) {
                if ( thisVal.match(numbers) ) {
                    $(".pr-useNumbers span").addClass("pr-ok")
                    useNumbersDone = true
                } else {
                    $(".pr-useNumbers span").removeClass("pr-ok")
                    useNumbersDone = false
                }
            }
        })
    }
}
