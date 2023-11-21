export default class Ajax {
  static post(data) {
    return jQuery.ajax({
      type: 'post',
      dataType: 'json',
      url: foodtecOptions.ajaxUrl,
      timout: 3000,
      data,
    });
  }
}
