<template>
  <span class="px-3 py-1 rounded-full text-label-sm font-label-sm border"
        :class="chipClass">
    {{ status }}
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  status: {
    type: String,
    required: true
  }
})

const chipClass = computed(() => {
  const classes = {
    Approved: 'bg-primary-container text-on-primary-container border-primary/10',
    Pending: 'bg-secondary-container text-on-secondary-container border-secondary/10',
    Rejected: 'bg-error-container text-on-error-container border-error/10'
  }
  return classes[props.status] || 'bg-surface-container text-on-surface-variant border-outline-variant/30'
})
</script>