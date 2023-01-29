/**
 *
 * @param {Element} alert
 * @param {Array<string>} messages
 */
function addMessagesToAlert(alert, messages)
{
    if (messages.length === 0) {
        return
    }

    alert.classList.remove('d-none')

    const container = alert.querySelector('.app-alert-messages')
    container.innerHTML = ''
    if (messages.length > 1) {
        const ul = document.createElement('ul')
        ul.classList.add('mb-0')
        messages.forEach(function (message) {
            const li = document.createElement('li')
            li.innerText = message
            ul.appendChild(li)
        })
        container.appendChild(ul)
    } else {
        const node = document.createTextNode(messages[0])
        container.appendChild(node)
    }
}

/**
 * @param {string} source
 * @param {string} type
 * @return {HTMLDivElement|null}
 */
function getAlertBySource(source, type)
{
    switch (source) {
        case window.app.api.methods.normalize:
            return  document.querySelector(`.alert-${type}.normalize-address-alert`)
        case window.app.api.methods.save:
            return document.querySelector(`.alert-${type}.save-address-alert`)
    }

    return null
}

/**
 * @param {string} opt
 * @return {Object<string, string|number>}
 */
function getAddressByOpt(opt)
{
    return getAddress(opt === 'opt-original' ? 'original' : 'normalized')
}

const addressConfirmationBlock = document.querySelector('.address-save-confirmation')
addressConfirmationBlock.querySelectorAll('input[type=radio]').forEach(function (elem) {
    elem.addEventListener('change', function (e) {
        const opt = e.target.id
        const address = getAddressByOpt(opt)
        for (const field in address) {
            document.getElementById('address-result-' + field).innerText = address[field]
        }
        setActiveAddressOpt(opt)
    })
})

const saveAddressModalElement = document.getElementById('save-address-modal')
const saveAddressModal = new bootstrap.Modal(saveAddressModalElement)
function openSaveAddressModal()
{
    addressConfirmationBlock.querySelector('input[type=radio]#opt-original + label').click()
    addressConfirmationBlock.querySelector('input[type=radio]#opt-original')
        .dispatchEvent(new Event('change'))
    saveAddressModal.show()
}

saveAddressModalElement.addEventListener('hidden.bs.modal', () => {
    const source = window.app.api.methods.save
    const alertError = getAlertBySource(source, 'danger')
    alertError.classList.add('d-none')
    const alertSuccess = getAlertBySource(source, 'success')
    alertSuccess.classList.add('d-none')
})

document.forms['address-form'].addEventListener('submit', async function (e) {
    e.preventDefault()
    const form = this
    const submit = form.querySelector('button[type=submit]')

    submit.disabled = true

    const json = await sendValidateAddressRequest(form)
    if (json.errors) {
        document.dispatchEvent(new CustomEvent('api.received-error', {
            detail: {
                source: window.app.api.methods.normalize,
                errors: json.errors,
            }
        }));
    } else {
        document.dispatchEvent(new CustomEvent('api.received-normalized-address', {
            detail: {
                source: window.app.api.methods.normalize,
                original: getFormValues(form),
                normalized: json,
            }
        }));
    }

    submit.disabled = false
})

document.getElementById('save-address-btn').addEventListener('click', async function (e) {
    const address = getAddressByOpt(getActiveAddressOpt())
    const submit = this

    submit.disabled = true

    const json = await sendSaveAddressRequest(address)
    if (json.errors) {
        document.dispatchEvent(new CustomEvent('api.received-error', {
            detail: {
                source: window.app.api.methods.save,
                errors: json.errors,
            }
        }));
    } else {
        document.dispatchEvent(new CustomEvent('api.received-save-address', {
            detail: {
                source: window.app.api.methods.save,
                result: json
            }
        }));
    }

    submit.disabled = false
})

document.addEventListener(
    'api.received-error',
    /**
     * @param {CustomEvent} e
     */
    function (e) {
        const detail = e.detail
        const alert = getAlertBySource(detail.source, 'danger')
        const errors = detail.errors
        if (alert) {
            addMessagesToAlert(alert, errors)
        } else {
            console.error(errors)
        }

        const alertSuccess = getAlertBySource(detail.source, 'success')
        if (alertSuccess) {
            alertSuccess.classList.add('d-none')
        }
    }
)

document.addEventListener(
    'api.received-normalized-address',
    /**
     * @param {CustomEvent} e
     */
    function (e) {
        const detail = e.detail
        const alert = getAlertBySource(detail.source, 'danger')

        alert.classList.add('d-none')

        setAddress('original', detail.original)
        setAddress('normalized', detail.normalized)

        openSaveAddressModal()
    }
)

document.addEventListener(
    'api.received-save-address',
    /**
     * @param {CustomEvent} e
     */
    function (e) {
        const detail = e.detail
        const alertSuccess = getAlertBySource(detail.source, 'success')
        const alertError = getAlertBySource(detail.source, 'danger')

        alertSuccess.classList.remove('d-none')
        alertError.classList.add('d-none')

        addMessagesToAlert(alertSuccess, detail.result)
    }
)
