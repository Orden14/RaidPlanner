import { Controller } from 'stimulus'

export default class extends Controller {
    connect () {
        this.passwordRequirements()
    }

    passwordRequirements () {
        const options = {
            numCharacters: 10,
            useLowercase: true,
            useUppercase: true,
            useNumbers: true,
            infoMessage: '',
            fadeTime: 300
        }

        options.infoMessage = "N'utilisez pas votre mot de passe Guild Wars 2."

        const numCharactersUI = '<li class="pr-numCharacters"><span></span>Minimum 10 caractères</li>'
        let useLowercaseUI = ''
        let useUppercaseUI = ''
        let useNumbersUI = ''

        if (options.useLowercase === true) {
            useLowercaseUI = '<li class="pr-useLowercase"><span></span>1 Minuscule</li>'
        }
        if (options.useUppercase === true) {
            useUppercaseUI = '<li class="pr-useUppercase"><span></span>1 Majuscule</li>'
        }
        if (options.useNumbers === true) {
            useNumbersUI = '<li class="pr-useNumbers"><span></span>1 Chiffre</li>'
        }

        const messageDiv = $(
            '<div id="pr-box"><i></i><div id="pr-box-inner"><p>' + options.infoMessage + '</p><ul>' + numCharactersUI + useLowercaseUI + useUppercaseUI + useNumbersUI + '</ul></div></div>'
        )

        let numCharactersDone = true
        let useLowercaseDone = true
        let useUppercaseDone = true
        let useNumbersDone = true

        const showMessage = () => {
            const prBoxElement = $('#pr-box')

            if (numCharactersDone === false || useLowercaseDone === false || useUppercaseDone === false || useNumbersDone === false) {
                if (prBoxElement.length === 0) {
                    const parentOffset = $(this.element).offset()
                    messageDiv.css({
                        top: parentOffset.top + $(this.element).outerHeight(),
                        left: parentOffset.left
                    })
                    $('body').append(messageDiv)
                }
                prBoxElement.fadeIn(options.fadeTime)
            }
        }

        const deleteMessage = () => {
            const targetMessage = $('#pr-box')
            targetMessage.fadeOut(options.fadeTime, function () {
                $(this).remove()
            })
        }

        const checkCompleted = () => {
            if (numCharactersDone === true && useLowercaseDone === true && useUppercaseDone === true && useNumbersDone === true) {
                deleteMessage()
            } else {
                showMessage()
            }
        }

        $(this.element).on('focus', () => {
            showMessage()
        })

        $(this.element).on('blur', () => {
            deleteMessage()
        })

        const lowerCase = /[a-z]/
        const upperCase = /[A-Z]/
        const numbers = /\d/

        $(this.element).on('keyup focus', function () {
            const thisVal = $(this).val()

            checkCompleted()

            if (thisVal.length >= options.numCharacters) {
                $('.pr-numCharacters span').addClass('pr-ok')
                numCharactersDone = true
            } else {
                $('.pr-numCharacters span').removeClass('pr-ok')
                numCharactersDone = false
            }

            if (options.useLowercase === true) {
                if (thisVal.match(lowerCase)) {
                    $('.pr-useLowercase span').addClass('pr-ok')
                    useLowercaseDone = true
                } else {
                    $('.pr-useLowercase span').removeClass('pr-ok')
                    useLowercaseDone = false
                }
            }

            if (options.useUppercase === true) {
                if (thisVal.match(upperCase)) {
                    $('.pr-useUppercase span').addClass('pr-ok')
                    useUppercaseDone = true
                } else {
                    $('.pr-useUppercase span').removeClass('pr-ok')
                    useUppercaseDone = false
                }
            }

            if (options.useNumbers === true) {
                if (thisVal.match(numbers)) {
                    $('.pr-useNumbers span').addClass('pr-ok')
                    useNumbersDone = true
                } else {
                    $('.pr-useNumbers span').removeClass('pr-ok')
                    useNumbersDone = false
                }
            }
        })
    }
}
