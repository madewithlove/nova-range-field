Nova.booting((Vue, router, store) => {
  Vue.component('index-nova-range-field', require('./components/IndexField'))
  Vue.component('detail-nova-range-field', require('./components/DetailField'))
  Vue.component('form-nova-range-field', require('./components/FormField'))
})
