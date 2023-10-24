import Validator from 'validatorjs';
 
// Multilanguage customization
 
import ca from 'validatorjs/src/lang/ca';
import es from 'validatorjs/src/lang/es';
import en from 'validatorjs/src/lang/en';
 
Validator.setMessages('ca', ca)
Validator.setMessages('es', es)
Validator.setMessages('en', en)
 
Validator.useLang(currentLocale || 'en');
 
export default Validator
