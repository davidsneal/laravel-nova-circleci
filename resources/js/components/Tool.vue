<template>
  <div>
    <heading class="mb-6">Deployments</heading>
    <card class="bg-white" >
      <field-wrapper
        :field="{
          name: 'Trigger',
          asHtml: true,
        }"
      >
        <div class="flex items-center w-full p-4">
          <span class="flex-1">
            You can trigger a deployment of your site by clicking the 'Deploy' button to the right.
          </span>
          <button
            @click="deploy"
            class="float-right btn btn-default btn-primary"
            :class="{
              'btn-disabled': this.disabled,
            }"
            v-text="buttonText"
          />
        </div>
      </field-wrapper>
    </card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      deploying: false,
    }
  },

  computed: {
    buttonText() {
      if (this.deploying) return 'Deploying'

      return 'Deploy'
    },

    disabled() {
      return this.deploying
    },
  },

  methods: {
    deploy() {
      if (this.disabled) return

      this.deploying = true

      Nova.request('/nova-vendor/laravel-nova-circleci/deploy')
        .then(() => {
          Nova.success('Deployment initiated successfully')
        })
        .catch((error) => {
          Nova.error('Failed to initiate the deployment')
        })
        .finally(() => {
          this.deploying = false
        })
    },
  },
}
</script>
