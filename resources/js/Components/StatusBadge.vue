<template>
  <div class="px-4 py-2 rounded-full border flex items-center gap-2"
       :class="[bgClass, textClass, borderClass]">
    <span v-if="icon" class="material-symbols-outlined text-[20px]" :style="{ fontVariationSettings: iconFillStyle }">{{ icon }}</span>
    <span class="font-label-md text-label-md">{{ label }}</span>
    <span class="font-headline-sm text-headline-sm">{{ count }}</span>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  count: [String, Number],
  label: String,
  colorClass: {
    type: String,
    default: 'surface-container-highest'
  },
  icon: String,
  iconFill: {
    type: Number,
    default: 0
  }
})

const bgClass = computed(() => {
  const classes = {
    'surface-container-highest': 'bg-surface-container-highest',
    'secondary-container': 'bg-secondary-container',
    'primary-container': 'bg-primary-container',
    'error-container': 'bg-error-container'
  }
  return classes[props.colorClass] || 'bg-surface-container-highest'
})

const textClass = computed(() => {
  const classes = {
    'surface-container-highest': 'text-on-surface-variant',
    'secondary-container': 'text-on-secondary-container',
    'primary-container': 'text-on-primary-container',
    'error-container': 'text-on-error-container'
  }
  return classes[props.colorClass] || 'text-on-surface-variant'
})

const borderClass = computed(() => {
  const classes = {
    'surface-container-highest': 'border-outline-variant',
    'secondary-container': 'border-secondary/20',
    'primary-container': 'border-primary/20',
    'error-container': 'border-error/20'
  }
  return `border ${classes[props.colorClass] || 'border-outline-variant'}`
})

const iconFillStyle = computed(() => {
  return `'FILL' ${props.iconFill}`
})
</script>