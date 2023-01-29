window.app = {
    api: {
        base: '',
        key: '',
        methods: {
            normalize: '/address/normalize',
            save: '/address/save'
        }
    },
    data: {
        address: {
            activeOpt: 'opt-original',
            original: {},
            normalized: {},
        },
    },
}

async function initConfig()
{
    const appConfigResponse = await fetch('/config.json')
    const appConfig = await appConfigResponse.json()
    window.app.api.base = appConfig.api.base
    window.app.api.key = appConfig.api.key
}

initConfig()
    .then(() => {
        console.log('Config fetched!')
    })

/**
 * @param {string} opt
 */
function setActiveAddressOpt(opt)
{
    window.app.data.address.activeOpt = opt
}

/**
 * @return {string}
 */
function getActiveAddressOpt()
{
    return window.app.data.address.activeOpt
}

/**
 * @param {string} key
 * @param {Object<string, string|number>} value
 */
function setAddress(key, value)
{
    window.app.data.address[key] = value
}

/**
 *
 * @param {string} key
 * @return {Object<string, string|number>} value
 */
function getAddress(key)
{
    return window.app.data.address[key]
}

/**
 * @param {HTMLFormElement} form
 * @return {Object<string, string|number>}
 */
function getFormValues(form)
{
    const formData = new FormData(form)
    const object = {}
    formData.forEach(function (value, key) {
        object[key] = value
    })
    return object
}

/**
 *
 * @param {string} path
 * @param {Object<string, string|number>} values
 * @return {Promise<any>}
 */
async function addressApiRequest(path, values)
{
    const response = await fetch(window.app.api.base + path, {
        method: 'POST',
        cors: 'cors',
        // credentials: 'include',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + window.app.api.key,
        },
        body: JSON.stringify(values),
    })

    if (!response.ok) {
        console.error(response.status + ': ' + response.statusText)
    }

    return response.json()
}

/**
 * @param {HTMLFormElement} form
 * @return Promise<any>
 */
async function sendValidateAddressRequest(form)
{
    const values = getFormValues(form)
    return addressApiRequest(window.app.api.methods.normalize, values)
}

/**
 * @param {Object<string, string|number>} values
 * @return Promise<any>
 */
async function sendSaveAddressRequest(values)
{
    return addressApiRequest(window.app.api.methods.save, values)
}
