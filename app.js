import CardBalance from './forms/card-balance';
import Contact from './forms/contact';
import Careers from './forms/careers';
import Catering from './forms/catering';
import ForgotPassword from './forms/forgot-password';
import Subscribe from './forms/subscribe';
import SMS from './forms/sms';
import Login from './forms/login';
import ResetPassword from './forms/reset-password';
import OrderTracker from './forms/order-tracker';
import Preorder from './modals/preorder';
import StoresMap from './maps/stores-map';
import EmbeddedOrdering from './modals/embedded-ordering';
import StoreSelect from './forms/store-select';
import Register from './forms/register';
import Signup from './forms/signup';
import FavoriteOrder from './forms/favorite-order';
import DeleteAccount from './forms/delete-account';
import UpdateProfile from './forms/update-profile';
import DeleteAddress from './forms/delete-address';
import UpdateAddress from './forms/update-address';
import LoyaltyPreferences from './forms/loyalty-preferences';
import Tremendous from './forms/tremendous';

if (!Object.entries) {
  Object.entries = (obj) => {
    const ownProps = Object.keys(obj);
    let i = ownProps.length;
    const resArray = new Array(i);
    while (i) {
      resArray[i] = [ownProps[i], obj[ownProps[i]]];
      i -= 1;
    }
    return resArray;
  };
}

jQuery(document).ready(($) => {
   if (foodtecOptions.preorder === 'on') {
    Preorder.init();
  }

  $('.url-tabs li:not(".logout") a').click(function tabClicked(e) {
    e.preventDefault();
    $(this).tab('show');
  });

  $('.fts-loading').fadeOut();

  if (typeof $('.carousel').carousel === 'function') {
    $('.carousel').carousel({
      pause: null,
    });
  }

  $('input[name=phone]').toArray().forEach(function(field){
    new Cleave(field, {
       numericOnly: true,
       delimiter: '-',
       blocks: [3, 3, 4]
    })
  });

  $('input[name=cardNumber]').toArray().forEach(function(field){
    new Cleave(field, {
      numericOnly: true,
      delimiter: '-',
      blocks: [4, 4, 4]
    })
  });
  Tremendous.init();
  CardBalance.init();
  Careers.init();
  Contact.init();
  Catering.init();
  Register.init();
  Signup.init();
  ForgotPassword.init();
  Subscribe.init();
  SMS.init();
  Login.init();
  ResetPassword.init();
  OrderTracker.init();
  StoreSelect.init();
  FavoriteOrder.init();
  DeleteAccount.init();
  UpdateProfile.init();
  LoyaltyPreferences.init();
  DeleteAddress.init();
  UpdateAddress.init();


  if (typeof (stores) !== 'undefined') {
    StoresMap.init();
  }

  if (foodtecOptions.preorder === 'on') {
    Preorder.init();
  }

  if (foodtecOptions.embeddedOrdering === 'on') {
    EmbeddedOrdering.init();
  }

  $('.url-tabs li:not(".logout") a').click(function tabClicked(e) {
    e.preventDefault();
    $(this).tab('show');
  });

  // Store the currently selected tab in the hash value.
  $('.url-tabs > li:not(".logout") > a').on('shown.bs.tab', (e) => {
    history.replaceState({}, '', $(e.target).attr('href'));
  });

  // On load of the page: switch to the currently selected tab.
  $(`.url-tabs li:not(".logout") a[href="${window.location.hash}"]`).tab('show');

  const rewardBoxes = document.querySelectorAll('.tremendous_request-form .reward-box');
  for (let i = 0; i < rewardBoxes.length; i++) {
    rewardBoxes[i].addEventListener('click', () => {
      document.querySelector('input[name="rewards_selection"]').value = rewardBoxes[i].id;
      for (let y = 0; y < rewardBoxes.length; y++) {
        rewardBoxes[y].classList.remove('active');
      }
      rewardBoxes[i].classList.add('active');
    });
  }
});
