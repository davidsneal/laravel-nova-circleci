<template>
  <div>
    <heading class="mb-6">Deployments</heading>
    <card class="bg-white">
      <div v-if="errored" class="flex flex-col items-center justify-center" style="min-height: 150px;">
        <div class="text-error">
          <svg class="error-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" fill-rule="evenodd"></path></svg>
        </div>
        <span>Something went wrong</span>
      </div>
      <div v-else-if="!loaded" class="flex items-center" style="min-height: 150px;">
        <div class="spinner">
          <div class="dot1" />
          <div class="dot2" />
        </div>
      </div>
      <div v-else>
        <field-wrapper
          :field="{
            name: 'Trigger',
            asHtml: true,
          }"
        >
          <div class="text-center w-full p-4">
            <label for="deployment_notes" class="block text-left">
              Notes
            </label>
            <span class="block help-text text-left mb-2">
              Enter at least 12 characters
            </span>
            <input
              class="w-full form-control form-input form-input-bordered mb-4"
              v-model="notes"
              id="deployment_notes"
              :disabled="!this.canData.can"
            />
            <button
              @click="deploy"
              class="btn btn-default btn-primary"
              :class="{
                'btn-disabled': disabled,
              }"
              v-text="buttonText"
            />
            <div class="py-4 help-text">
              Deployments are limited to once every <span class="font-bold">{{ this.canData.throttle }} minutes</span>.
              <span v-if="!this.canData.can">
                Please wait another <span class="font-bold">{{ this.canData.wait }} minutes</span> before deploying again.
              </span>
            </div>
          </div>
        </field-wrapper>
        <field-wrapper
          v-for="pipeline in pipelines"
          :key="pipeline.pipeline_number"
          :field="{
            name: pipeline.pipeline_number,
            asHtml: true,
          }"
        >
          <div class="w-full flex p-4">
            <span class="font-bold">
              {{ pipeline.user }}
            </span>
            <span class="flex-1 px-4">
              {{ pipeline.notes }}
            </span>
            <span class="help-text pr-4">
              {{ pipeline.ago }}
            </span>
            <span>
              <span
                class="whitespace-no-wrap px-2 py-1 rounded-full uppercase text-xs font-bold text-white"
                :class="{
                  'bg-success': pipeline.state === 'created',
                  'bg-info': pipeline.state === 'loading',
                  'bg-black': pipeline.state === 'pending',
                  'bg-danger': pipeline.state === 'unloaded' || pipeline.state === 'errored',
                }"
              >
                {{ pipeline.state }}
              </span>
            </span>
          </div>
        </field-wrapper>
      </div>
    </card>
  </div>
</template>

<script>
import moment from 'moment'

export default {
  data() {
    return {
      canData: {
        can: false,
      },
      deploying: false,
      errored: false,
      loaded: false,
      notes: '',
      pipelines: [],
    }
  },

  computed: {
    buttonText() {
      if (this.deploying) return 'Deploying'

      return 'Deploy'
    },

    disabled() {
      if (!this.loaded) return true

      if (!this.canData.can) return true

      if (this.notes.length < 12) return true

      return this.deploying
    },
  },

  async mounted() {
    await this.init()
  },

  methods: {
    async init() {
      this.loaded = false

      await Nova.request('/nova-vendor/laravel-nova-circleci/can')
        .then((response) => {
          this.canData = response.data
        })
        .catch((error) => {
          this.errored = true
        })

      await Nova.request('/nova-vendor/laravel-nova-circleci/pipelines')
        .then((response) => {
          this.pipelines = response.data.map((pipeline) => {
            pipeline.ago = moment(pipeline.createdAt).fromNow()

            return pipeline
          })
        })
        .catch((error) => {
          this.errored = true
        })

        this.loaded = true

        this.pipelines.map((pipeline) => {
          this.getPipeline(pipeline.pipeline_id)
        })
    },

    deploy() {
      if (this.disabled) return

      this.deploying = true

      Nova.request(`/nova-vendor/laravel-nova-circleci/deploy?notes=${this.notes}`)
        .then(() => {
          Nova.success('Deployment initiated successfully')
          this.init()
        })
        .catch((error) => {
          Nova.error('Failed to initiate the deployment')
        })
        .finally(() => {
          this.deploying = false
        })
    },

    getPipeline(pipeline_id) {
      Nova.request(`/nova-vendor/laravel-nova-circleci/pipelines/${pipeline_id}`)
        .then((response) => {
          this.updatePipelineState(pipeline_id, response.data.state)
        })
        .catch((error) => {
          this.updatePipelineState(pipeline_id, 'Unloaded')
        })
    },

    updatePipelineState(pipeline_id, state) {
      this.pipelines.map((pipeline) => {
        if (pipeline.pipeline_id === pipeline_id) {
          pipeline.state = state
        }
      })
    },
  },
}
</script>

<style scoped>
  .text-error {
    color: var(--danger);
  }

  .error-icon {
    width: 50px;
    height: auto;
  }

  .spinner {
    margin: 100px auto;
    width: 40px;
    height: 40px;
    position: relative;
    text-align: center;
    animation: sk-rotate 1.5s infinite linear;
  }

  .dot1, .dot2 {
    width: 60%;
    height: 60%;
    display: inline-block;
    position: absolute;
    top: 0;
    border-radius: 100%;
    animation: sk-bounce 1.5s infinite ease-in-out;
    background-color: var(--primary);
  }

  .dot2 {
    top: auto;
    bottom: 0;
    animation-delay: -0.75s;
  }

  @keyframes sk-rotate { 100% { transform: rotate(360deg) }}

  @keyframes sk-bounce {
    0%, 100% {
      transform: scale(0.0);
    } 50% {
      transform: scale(1.0);
    }
  }
</style>
