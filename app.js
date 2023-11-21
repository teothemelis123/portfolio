import Register from './forms/register';


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
  
  Register.init();
  


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
