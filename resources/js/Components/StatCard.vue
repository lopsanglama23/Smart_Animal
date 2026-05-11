<template>
  <div class="bg-surface-container-lowest p-md rounded-xl border border-outline-variant/30 shadow-sm hover:border-primary transition-all"
       :class="{ 'hover:border-error': colorClass === 'error', 'hover:border-secondary': colorClass === 'secondary' }">
    <p class="text-label-sm text-on-surface-variant mb-1">{{ title }}</p>
    <div class="flex items-baseline gap-2">
      <span class="text-headline-md font-headline-md" :class="`text-${colorClass}`">{{ value }}</span>
      <span v-if="trend" class="text-label-sm flex items-center" :class="`text-${colorClass}`">
        <span class="material-symbols-outlined text-[14px]">{{ trendDirection === 'up' ? 'arrow_upward' : 'arrow_downward' }}</span>
        {{ trend }}
      </span>
      <span v-if="status" class="text-label-sm flex items-center" :class="`text-${statusColor}`">
        <span v-if="statusColor === 'error'" class="material-symbols-outlined text-[14px]">warning</span>
        <span v-else-if="statusColor === 'primary'" class="material-symbols-outlined text-[14px]">verified</span>
        <span v-else class="material-symbols-outlined text-[14px]">trending_up</span>
        {{ status }}
      </span>
    </div>
    <div class="mt-4 h-1 bg-surface-container-low rounded-full overflow-hidden">
      <div class="h-full rounded-full" :class="`bg-${colorClass}`" :style="{ width: `${progressPercentage}%` }"></div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  value: [String, Number],
  trend: String,
  trendDirection: String,
  status: String,
  statusColor: String,
  colorClass: {
    type: String,
    default: 'primary'
  },
  progressPercentage: Number
})
</script>