Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'laravel-nova-circleci',
      path: '/laravel-nova-circleci',
      component: require('./components/Tool'),
    },
  ])
})
