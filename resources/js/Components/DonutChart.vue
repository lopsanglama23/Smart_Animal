<template>
  <svg :width="size" :height="size" :viewBox="`0 0 ${size} ${size}`" class="-rotate-90">
    <circle
      v-for="(segment, index) in segments"
      :key="segment.label"
      :cx="size / 2"
      :cy="size / 2"
      fill="transparent"
      :r="radius"
      :stroke="segment.color"
      :stroke-width="strokeWidth"
      :stroke-dasharray="`${circumference * segment.percentage / 100} ${circumference}`"
      :stroke-dashoffset="cumulativeOffset(index)"
    />
  </svg>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  segments: {
    type: Array,
    required: true
  },
  size: {
    type: Number,
    default: 192
  },
  strokeWidth: {
    type: Number,
    default: 3
  }
})

const radius = computed(() => (props.size / 2) - (props.strokeWidth / 2))
const circumference = computed(() => 2 * Math.PI * radius.value)

const cumulativeOffset = (index) => {
  let offset = 0
  for (let i = 0; i < index; i++) {
    offset += (circumference.value * props.segments[i].percentage / 100)
  }
  return -offset
}
</script>