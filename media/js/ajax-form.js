const frms = document.querySelectorAll('.ajax-form');

var spinnerTemplate = `
<div role="status" class="spinner hidden">
    <svg aria-hidden="true" class="inline w-4 h-4 text-gray-200 animate-spin fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
    </svg>
    <span class="sr-only">Loading...</span>
</div>`;

var successModalTemplate = `
<div id="ajax-form-success-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-md h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative p-4 text-center rounded-lg shadow bg-gray-800 sm:p-5">
            <button type="button" class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent rounded-lg text-sm p-1.5 ml-auto inline-flex items-center hover:bg-gray-600 hover:text-white" data-modal-toggle="ajax-form-success-modal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="w-12 h-12 rounded-full bg-green-900 p-2 flex items-center justify-center mx-auto mb-3.5">
                <svg aria-hidden="true" class="w-8 h-8 text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Success</span>
            </div>
            <p class="mb-4 text-lg font-semibold text-white" id="success-message">Povedlo se!</p>
            <button data-modal-toggle="ajax-form-success-modal" type="button" class="py-2 px-3 text-sm font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-900">
                Pokračovat
            </button>
        </div>
    </div>
</div>
`

var errorModalTemplate = `
<div id="ajax-form-error-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-md h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative p-4 text-center rounded-lg shadow bg-gray-800 sm:p-5">
            <button type="button" class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent rounded-lg text-sm p-1.5 ml-auto inline-flex items-center hover:bg-gray-600 hover:text-white" data-modal-toggle="ajax-form-error-modal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="w-12 h-12 rounded-full bg-red-900 p-2 flex items-center justify-center mx-auto mb-3.5">
                <svg  class="w-8 h-8 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                <span class="sr-only">error</span>
            </div>
            <p class="mb-4 text-lg font-semibold text-white" id="error-message">Něco se nepovedlo!</p>
            <button data-modal-toggle="ajax-form-error-modal" type="button" class="py-2 px-3 text-sm font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-primary-900">
                Pokračovat
            </button>
        </div>
    </div>
</div>
`

$("body").append(successModalTemplate);
$("body").append(errorModalTemplate);

const successModal = new Modal(document.getElementById('ajax-form-success-modal'));
const errorModal = new Modal(document.getElementById('ajax-form-error-modal'));

frms.forEach(frm => {
    var form = $(frm);
    var submitButton = form.children("button");
    var ourModalId = form.parent().parent().parent().parent().attr("id");
    var callbackName = form.data("ajax-callback");
    var timeouts = []
    
    submitButton.prepend(spinnerTemplate);
    var spinner = submitButton.children(".spinner");

    frm.addEventListener('submit', function(e) {
    if (frm.checkValidity()) {
        spinner.show();
        submitButton.prop("disabled", true);
        var actionUrl = form.attr("action");
        var formData = form.serializeArray().reduce(function(obj, item) {
            if(item.name.includes("[]")){
                if(obj[item.name.replace("[]","")] == undefined){
                    obj[item.name.replace("[]","")] = [item.value];
                }else{
                    obj[item.name.replace("[]","")].push(item.value);
                }
            }else{
                obj[item.name] = item.value;
            }
            return obj;
        }, {});
        try {
            postData(actionUrl,formData).then((result)=>{
                if(result.status == "success"){
                    ajaxOnSuccess(ourModalId,result,timeouts);
                    window[callbackName](result);
                }
                if(result.status == "error"){
                    ajaxOnError(ourModalId,result,timeouts);
                }
            }).done();
        } catch (error) {
            timeouts.push(setTimeout(ajaxOnError,1000,ourModalId,timeouts,null));
        }
    }
    
    submitButton.prop("disabled", false);
    spinner.hide();
    e.preventDefault();
    });
});

function ajaxOnError(modalId,result,timeouts) {
    document.getElementById(modalId).dispatchEvent(
            new KeyboardEvent("keydown", {
            altKey: false,
            code: "Escape",
            ctrlKey: false,
            isComposing: false,
            key: "Escape",
            location: 0,
            metaKey: false,
            repeat: false,
            shiftKey: false,
            which: 27,
            charCode: 0,
            keyCode: 27,
            })
        );
        
        errorModal.show();
        if(result != null){
            $("#error-message").text(result.message);
        }
    }

function ajaxOnSuccess(modalId,result,timeouts) {
    for (var i=0; i<timeouts.length; i++) {
        clearTimeout(timeouts[i]);
    }
    document.getElementById(modalId).dispatchEvent(
            new KeyboardEvent("keydown", {
            altKey: false,
            code: "Escape",
            ctrlKey: false,
            isComposing: false,
            key: "Escape",
            location: 0,
            metaKey: false,
            repeat: false,
            shiftKey: false,
            which: 27,
            charCode: 0,
            keyCode: 27,
            })
        );

        successModal.show();
        if(result != null){
            $("#success-message").text(result.message);
        }
}