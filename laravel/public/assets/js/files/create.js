// Load our customized validationjs library
import Validator from '../validator'
 
// Submit form ONLY when validation is OK
const form = document.getElementById("create")
 
form.addEventListener("submit", function( event ) {
   // Reset errors messages
   // Create validation
   let data = {
       "upload": document.getElementsByName("upload")[0].value,
   }
   let rules = {
       "upload": "required",
   }
   let validation = new Validator(data, rules)
   // Validate fields
   if (validation.passes()) {
       // Allow submit form (do nothing)
       console.log("Validation OK")
   } else {
       // Get error messages

       let errors = validation.errors.all()
       console.log(errors)
       // Show error messages
       for(let inputName in errors) {
           let menssage=errors[inputName]
        document.getElementById("err").innerHTML=menssage;

       }
       // Avoid submit
       event.preventDefault()
       return false
   }
})
