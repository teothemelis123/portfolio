import FormHandler from './form-handler';

export default class Register {
  static init() {
    const data = ['favoriteStore', 'firstName', 'lastName', 'email', 'password', 'phone', 'loyaltyCard', 'giftCard', 'signupForLoyalty', 'sendNewsletters', 'acceptPolicy', 'birthDay', 'birthMonth'];
    const registerFormHandler = new FormHandler('.register-form', 'register', data);
    registerFormHandler.registerSubmit((form, response) => {
      if (!response) {
        registerFormHandler.displayMessage(form, 'You have successfully registered.', 'success');
      } else if (typeof response.message !== 'undefined' && response.message !== '') {
        registerFormHandler.displayMessage(form, response.message, 'danger');
      } else {
        registerFormHandler.displayGenericErrorMessage(form);
      }
    });
  }
}
