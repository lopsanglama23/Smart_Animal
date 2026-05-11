<template>
  <div class="w-full max-w-[120px]">
    <div class="flex justify-between items-center mb-1">
      <span class="text-label-sm font-label-sm" :class="scoreColorClass">{{ score }}%</span>
      <span class="text-[10px] uppercase font-bold" :class="labelColorClass">{{ label }}</span>
    </div>
    <div class="h-1.5 w-full bg-outline-variant/30 rounded-full overflow-hidden">
      <div class="h-full rounded-full" :class="barClass" :style="{ width: `${score}%` }"></div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  score: Number,
  label: String
})

const scoreColorClass = computed(() => {
  if (props.score >= 80) return 'text-primary'
  if (props.score >= 60) return 'text-secondary'
  if (props.score >= 40) return 'text-on-surface-variant/40'
  return 'text-on-surface-variant/40'
})

const labelColorClass = computed(() => {
  if (props.score >= 80) return 'text-secondary'
  if (props.score >= 60) return 'text-on-surface-variant/70'
  return 'text-error'
})

const barClass = computed(() => {
  if (props.score >= 80) return 'bg-gradient-to-r from-secondary to-primary'
  if (props.score >= 60) return 'bg-secondary'
  return 'bg-error/40'
})
</script>