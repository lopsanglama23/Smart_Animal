<template>
  <div class="text-on-background">
    <!-- SideNavBar -->
    <aside class="fixed left-0 top-0 h-full w-[260px] bg-surface-container flex flex-col py-6 space-y-2 border-r border-outline-variant/20 z-50">
      <div class="px-6 mb-8">
        <h1 class="text-headline-md font-headline-md font-bold text-on-surface">Pawfect</h1>
        <p class="text-label-md font-label-md text-on-surface-variant/70">AI Admin Portal</p>
      </div>
      <nav class="flex-1 space-y-1">
        <div v-for="item in navItems" :key="item.label"
             class="flex items-center gap-3 px-4 py-3 rounded-full mx-4 transition-colors cursor-pointer"
             :class="item.active ? 'bg-secondary-container text-primary' : 'text-on-surface-variant hover:bg-surface-container-high'">
          <span class="material-symbols-outlined">{{ item.icon }}</span>
          <span class="font-label-md text-label-md" :class="item.active ? 'font-bold' : 'font-medium'">{{ item.label }}</span>
        </div>
      </nav>
      <div class="px-4 mt-auto">
        <button class="w-full flex items-center justify-center gap-2 bg-primary text-on-primary py-3 rounded-lg font-label-md hover:bg-primary-container transition-all active:scale-95">
          <span class="material-symbols-outlined">open_in_new</span>
          View Platform
        </button>
      </div>
    </aside>

    <!-- TopAppBar -->
    <header class="fixed top-0 right-0 z-40 h-[72px] flex justify-between items-center w-[calc(100%-260px)] ml-[260px] px-gutter bg-surface/80 backdrop-blur-md border-b border-outline-variant/30">
      <div class="flex items-center gap-4">
        <div class="relative w-80">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
          <input v-model="searchQuery"
                 class="w-full bg-surface-container-low border-none rounded-full pl-10 pr-4 py-2 focus:ring-2 focus:ring-primary text-body-sm"
                 placeholder="Search analytics..." type="text" />
        </div>
      </div>
      <div class="flex items-center gap-4">
        <button class="p-2 hover:bg-surface-container-low rounded-full transition-all">
          <span class="material-symbols-outlined text-on-surface-variant">notifications</span>
        </button>
        <button class="p-2 hover:bg-surface-container-low rounded-full transition-all">
          <span class="material-symbols-outlined text-on-surface-variant">settings</span>
        </button>
        <button class="p-2 hover:bg-surface-container-low rounded-full transition-all">
          <span class="material-symbols-outlined text-on-surface-variant">help_outline</span>
        </button>
        <div class="h-10 w-10 rounded-full overflow-hidden border border-outline-variant ml-2">
          <img :src="userAvatar" alt="Admin User Profile" class="w-full h-full object-cover" />
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="ml-[260px] pt-[72px] min-h-screen p-gutter max-w-container-max mx-auto space-y-md">
      <!-- Header Section -->
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 py-4">
        <div>
          <h2 class="font-headline-lg text-headline-lg text-on-background">Post-Adoption ML Monitor</h2>
          <div class="flex items-center gap-3 mt-2">
            <span class="inline-flex items-center gap-1.5 bg-primary-container text-on-primary-container px-3 py-1 rounded-full text-label-sm">
              <span class="w-2 h-2 rounded-full bg-on-primary-container animate-pulse"></span>
              Tracking {{ activeAdoptionsCount }} active adoptions
            </span>
            <span class="inline-flex items-center gap-1.5 bg-surface-container-high text-on-surface-variant px-3 py-1 rounded-full text-label-sm">
              <span class="material-symbols-outlined text-[14px]">schedule</span>
              Last model run: 2 hours ago
            </span>
          </div>
        </div>
        <button class="flex items-center gap-2 bg-primary text-on-primary px-6 py-2.5 rounded-lg font-label-md shadow-sm hover:shadow-md transition-all">
          <span class="material-symbols-outlined">download</span>
          Export Report
        </button>
      </div>

      <!-- Row 1: Stat Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
        <StatCard
          title="Active Adoptions"
          :value="activeAdoptionsCount"
          trend="+4%"
          trendDirection="up"
          colorClass="primary"
          :progressPercentage="75"
        />
        <StatCard
          title="High Risk Cases"
          :value="highRiskCount"
          status="Critical"
          statusColor="error"
          colorClass="error"
          :progressPercentage="15"
        />
        <StatCard
          title="Avg Health Score"
          value="81%"
          status="Good"
          statusColor="secondary"
          colorClass="secondary"
          :progressPercentage="81"
        />
        <StatCard
          title="Successful at 90 Days"
          value="94%"
          status="Stable"
          statusColor="primary"
          colorClass="primary"
          :progressPercentage="94"
        />
      </div>

      <!-- Row 2: Charts -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
        <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant/30 shadow-sm">
          <h3 class="font-headline-sm text-headline-sm mb-6">Return Risk Distribution</h3>
          <div class="relative h-[240px] flex items-center justify-center">
            <DonutChart :segments="riskDistribution" :size="192" strokeWidth="3" />
            <div class="absolute flex flex-col items-center">
              <span class="text-headline-md font-bold">{{ activeAdoptionsCount }}</span>
              <span class="text-label-sm text-on-surface-variant">Total</span>
            </div>
          </div>
          <div class="flex justify-center gap-6 mt-6">
            <div v-for="segment in riskDistribution" :key="segment.label" class="flex items-center gap-2">
              <span class="w-3 h-3 rounded-full" :style="{ backgroundColor: segment.color }"></span>
              <span class="text-label-sm">{{ segment.label }} ({{ segment.percentage }}%)</span>
            </div>
          </div>
        </div>

        <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant/30 shadow-sm">
          <div class="flex justify-between items-center mb-6">
            <h3 class="font-headline-sm text-headline-sm">Health Score Trend</h3>
            <select v-model="healthTrendPeriod" class="bg-surface-container-low border-none rounded-lg text-label-sm py-1 pl-2 pr-8">
              <option value="6months">Last 6 months</option>
              <option value="ytd">Year to date</option>
            </select>
          </div>
          <div class="h-[240px] relative flex items-end justify-between px-2">
            <div v-for="(item, idx) in healthTrendData" :key="idx"
                 class="w-12 bg-primary-container/20 rounded-t-sm relative group"
                 :style="{ height: `${item.maxHeight}%` }">
              <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-on-background text-on-primary text-[10px] px-2 py-1 rounded hidden group-hover:block group-hover:flex">
                {{ item.percentage }}%
              </div>
              <div class="absolute bottom-0 w-full bg-primary rounded-t-sm"
                   :style="{ height: `${item.percentage}%`, maxHeight: '100%' }"></div>
            </div>
          </div>
          <div class="flex justify-between mt-4 text-label-sm text-on-surface-variant px-2">
            <span v-for="month in healthTrendMonths" :key="month">{{ month }}</span>
          </div>
        </div>
      </div>

      <!-- Row 3: High Risk Table -->
      <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden">
        <div class="p-md border-b border-outline-variant/20 flex justify-between items-center">
          <h3 class="font-headline-sm text-headline-sm text-error flex items-center gap-2">
            <span class="material-symbols-outlined">report</span>
            High Risk Adoptions — Needs Attention
          </h3>
          <button class="text-primary text-label-md font-bold hover:underline">View All Critical Cases</button>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left">
            <thead>
              <tr class="bg-surface-container-low text-on-surface-variant text-label-sm">
                <th class="px-md py-4">Adopter</th>
                <th class="px-md py-4">Pet</th>
                <th class="px-md py-4">Adoption Date</th>
                <th class="px-md py-4 w-48">Risk Score</th>
                <th class="px-md py-4">Last Check-in</th>
                <th class="px-md py-4">Health Score</th>
                <th class="px-md py-4 text-center">Missed</th>
                <th class="px-md py-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
              <tr v-for="riskCase in highRiskCases" :key="riskCase.id" class="hover:bg-surface-container-low transition-colors group">
                <td class="px-md py-4 font-label-md">{{ riskCase.adopter }}</td>
                <td class="px-md py-4 flex items-center gap-3">
                  <img :src="riskCase.petImage" :alt="riskCase.petName" class="w-10 h-10 rounded-lg object-cover" />
                  <span class="font-label-md">{{ riskCase.petName }} ({{ riskCase.petBreed }})</span>
                </td>
                <td class="px-md py-4 text-body-sm">{{ riskCase.adoptionDate }}</td>
                <td class="px-md py-4">
                  <div class="flex items-center gap-3">
                    <div class="flex-1 h-2 bg-surface-container-high rounded-full overflow-hidden">
                      <div class="h-full bg-error" :style="{ width: `${riskCase.riskScore}%` }"></div>
                    </div>
                    <span class="text-error font-bold text-label-sm">{{ riskCase.riskScore }}</span>
                  </div>
                </td>
                <td class="px-md py-4 text-body-sm">{{ riskCase.lastCheckin }}</td>
                <td class="px-md py-4">
                  <span class="px-2 py-1 rounded text-[10px] font-bold uppercase"
                        :class="riskCase.healthScoreClass">
                    {{ riskCase.healthScoreLabel }} {{ riskCase.healthScore }}%
                  </span>
                </td>
                <td class="px-md py-4 text-center text-error font-bold">{{ riskCase.missedCheckins }}</td>
                <td class="px-md py-4">
                  <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button class="p-2 text-primary hover:bg-primary/10 rounded-lg" title="Contact">
                      <span class="material-symbols-outlined text-[20px]">mail</span>
                    </button>
                    <button class="p-2 text-primary hover:bg-primary/10 rounded-lg" title="Schedule Visit">
                      <span class="material-symbols-outlined text-[20px]">event_repeat</span>
                    </button>
                    <button class="p-2 text-primary hover:bg-primary/10 rounded-lg" title="View Timeline">
                      <span class="material-symbols-outlined text-[20px]">history</span>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Row 4: Sentiment & Bar Chart -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
        <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant/30 shadow-sm">
          <h3 class="font-headline-sm text-headline-sm mb-6">Check-in Completion Rate</h3>
          <div class="space-y-4">
            <div v-for="checkin in checkinRates" :key="checkin.period" class="space-y-1">
              <div class="flex justify-between text-label-sm">
                <span>{{ checkin.period }}</span>
                <span class="font-bold">{{ checkin.rate }}%</span>
              </div>
              <div class="h-4 bg-surface-container-low rounded-full">
                <div class="h-full rounded-full"
                     :class="checkin.barClass"
                     :style="{ width: `${checkin.rate}%` }"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant/30 shadow-sm">
          <h3 class="font-headline-sm text-headline-sm mb-6">Sentiment Analysis of Check-in Notes</h3>
          <div class="flex items-center gap-4 mb-8">
            <div v-for="sentiment in sentimentDistribution" :key="sentiment.label"
                 class="h-3 rounded-full"
                 :class="sentiment.barClass"
                 :style="{ width: `${sentiment.percentage}%` }"></div>
          </div>
          <div class="space-y-4">
            <div v-for="review in sentimentReviews" :key="review.id"
                 class="p-3 bg-surface-container-low rounded-lg"
                 :class="review.borderClass">
              <p class="text-body-sm italic">{{ review.text }}</p>
              <div class="mt-2 flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase" :class="review.sentimentClass">
                  {{ review.sentimentLabel }} ({{ review.score }})
                </span>
                <span class="text-[10px] text-on-surface-variant">{{ review.timeAgo }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Row 5: Timeline Search -->
      <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant/30 shadow-sm">
        <h3 class="font-headline-sm text-headline-sm mb-4">View Any Adoption Timeline</h3>
        <div class="relative max-w-2xl">
          <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
          <input v-model="timelineSearch"
                 class="w-full bg-surface-container-low border border-outline-variant/30 rounded-xl pl-12 pr-4 py-4 focus:ring-2 focus:ring-primary focus:border-transparent text-body-md"
                 placeholder="Search by Adopter Name, Pet ID, or Adoption Reference..." type="text" />
        </div>
        <div class="mt-4 flex gap-2">
          <span class="text-label-sm text-on-surface-variant">Recent Searches:</span>
          <button v-for="search in recentSearches" :key="search"
                  class="text-label-sm text-primary hover:underline"
                  @click="timelineSearch = search">
            {{ search }},
          </button>
        </div>
      </div>

      <!-- Row 6: Model Performance -->
      <div class="bg-inverse-surface text-inverse-on-surface p-xl rounded-2xl shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full blur-3xl -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-secondary/10 rounded-full blur-3xl -ml-10 -mb-10"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-xl">
          <div>
            <h3 class="text-headline-md font-headline-md mb-2">Model Performance</h3>
            <p class="text-body-md text-surface-variant opacity-80 max-w-lg">
              Post-Adoption Risk Model (v4.2) predicts return probability based on behavioral check-ins,
              medical updates, and adopter demographics.
            </p>
          </div>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-gutter flex-1">
            <div class="text-center p-4 bg-on-surface-variant/10 rounded-xl backdrop-blur-sm">
              <span class="block text-headline-sm font-bold text-primary-fixed">91%</span>
              <span class="text-label-sm text-surface-variant opacity-70">Accuracy</span>
            </div>
            <div class="text-center p-4 bg-on-surface-variant/10 rounded-xl backdrop-blur-sm">
              <span class="block text-headline-sm font-bold text-secondary-fixed">2.4%</span>
              <span class="text-label-sm text-surface-variant opacity-70">False Positive</span>
            </div>
            <div class="text-center p-4 bg-on-surface-variant/10 rounded-xl backdrop-blur-sm">
              <span class="block text-headline-sm font-bold">12.8k</span>
              <span class="text-label-sm text-surface-variant opacity-70">Data Points</span>
            </div>
            <div class="flex items-center justify-center">
              <button @click="retrainModel"
                      class="bg-primary-fixed text-on-primary-fixed px-6 py-3 rounded-lg font-bold hover:bg-primary transition-all flex items-center gap-2 group">
                <span class="material-symbols-outlined group-hover:rotate-180 transition-transform">autorenew</span>
                Retrain Model
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import StatCard from './components/StatCard.vue'
import DonutChart from './components/DonutChart.vue'

// Navigation items
const navItems = ref([
  { label: 'Overview', icon: 'dashboard', active: false },
  { label: 'Pets', icon: 'pets', active: false },
  { label: 'Adoptions', icon: 'favorite', active: false },
  { label: 'Shop', icon: 'shopping_bag', active: false },
  { label: 'Orders', icon: 'receipt_long', active: false },
  { label: 'Users', icon: 'group', active: false },
  { label: 'Matching', icon: 'psychology', active: false },
  { label: 'Analytics', icon: 'analytics', active: false },
  { label: 'ML Insights', icon: 'insights', active: true }
])

const userAvatar = 'https://lh3.googleusercontent.com/aida-public/AB6AXuAZdElJ-g3-_VI-G1tit4iD_d0loHhWGx70PWa4LgJeF8AW0OARKjlJ3Wemu4bysKstI5-grYPtj_CHGFUIbcZx1uR_NkdDqZzOCG7tecGdIkkyqRmyZrLMelUpPHWUr5ACxL5_9hk-aRJn07DLC4MgiG-VQ62vfRJidUkN7AK8lJyKeE-sWet3QeHMLyHfavEi4ySXSlvmPjnrF3MUQp0nLsHR7GbqlMlMiCaxC8D9rPj5CCK8YCY6e99TUETYaUKDrY8NuXtzmR0'

// Search states
const searchQuery = ref('')
const timelineSearch = ref('')

// Data
const activeAdoptionsCount = ref(312)
const highRiskCount = ref(14)

const riskDistribution = ref([
  { label: 'Low', percentage: 70, color: '#00694c' },
  { label: 'Med', percentage: 20, color: '#ff8c00' },
  { label: 'High', percentage: 10, color: '#ba1a1a' }
])

const healthTrendPeriod = ref('6months')
const healthTrendMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']
const healthTrendData = ref([
  { percentage: 50, maxHeight: 60 },
  { percentage: 55, maxHeight: 65 },
  { percentage: 62, maxHeight: 70 },
  { percentage: 68, maxHeight: 75 },
  { percentage: 78, maxHeight: 85 },
  { percentage: 81, maxHeight: 90 }
])

const highRiskCases = ref([
  {
    id: 1,
    adopter: 'Sarah Jenkins',
    petName: 'Buddy',
    petBreed: 'Beagle',
    petImage: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAl1Ug81869O3KFampFU6sHOsklnyjtKE5ivxLq2rnpBsYBwMdCufY0Ip0Cvqm_2Pxgj9Y0pKxM_3tTQnsLLc791_F6EAqzK5-SdP4_hLyDuqpx1TkmfcIM_vRyf6R3jIyzkr0IcULrccd3nlOZ3OgLgehD647z6Zm1EcAMsP4Kli84Dc5fwk0Wepqcas5gPQ5zH-seENzW3G2RkjiSG8oilkNT7bEyZb-1g9YgffSRCzi50AISfmZqTlyZk-37voRG0t9uAgGOkTU',
    adoptionDate: 'Oct 12, 2023',
    riskScore: 88,
    lastCheckin: '2 days ago',
    healthScore: 42,
    healthScoreLabel: 'Critical',
    healthScoreClass: 'bg-error-container text-on-error-container',
    missedCheckins: 3
  },
  {
    id: 2,
    adopter: 'Michael Chen',
    petName: 'Luna',
    petBreed: 'Tabby',
    petImage: 'https://lh3.googleusercontent.com/aida-public/AB6AXuA1tM4N6U87h9toSQCb9BPvjI7hZmGIfbCUaNIQK3Md3mEpVsSRpLqZRB2zBS2NSA2-efBZsB4ocKk-CUTabzpBJjvG_kQNLEauc9-R82EkICovwXcGzf-XTnViaWAkYZNurlwbQT3YYW8NmQoUaWv_DvB2kP10VlXyYNpM_6GIvAK1kIGmjE22_UGqZpIhmCFFzcwARxK9IVD1LjcyTFON0RP913V8ZTYPdJyY1TOgHW31R4lMkjw4a0iBgjU6_VhZpCL0VIrZW2w',
    adoptionDate: 'Nov 05, 2023',
    riskScore: 74,
    lastCheckin: '8 days ago',
    healthScore: 58,
    healthScoreLabel: 'Low',
    healthScoreClass: 'bg-tertiary-container text-on-tertiary-container',
    missedCheckins: 5
  }
])

const checkinRates = ref([
  { period: 'Week 1 Post-Adoption', rate: 98, barClass: 'bg-primary' },
  { period: 'Month 1 Check-in', rate: 84, barClass: 'bg-primary' },
  { period: 'Month 3 Check-in', rate: 62, barClass: 'bg-secondary-container' },
  { period: 'Annual Check-in', rate: 41, barClass: 'bg-error-container' }
])

const sentimentDistribution = ref([
  { label: 'Negative', percentage: 15, barClass: 'bg-error' },
  { label: 'Neutral', percentage: 25, barClass: 'bg-tertiary-fixed-dim' },
  { label: 'Positive', percentage: 60, barClass: 'bg-primary' }
])

const sentimentReviews = ref([
  {
    id: 1,
    text: '"Buddy has settled in perfectly. He\'s already mastered \'sit\' and loves his new bed. We couldn\'t be happier!"',
    sentimentLabel: 'Positive Sentiment',
    score: '0.94',
    timeAgo: '2 hours ago',
    sentimentClass: 'text-primary',
    borderClass: 'border-l-4 border-primary'
  },
  {
    id: 2,
    text: '"A few accidents in the house, but we\'re working on it. He\'s quite energetic during the nights."',
    sentimentLabel: 'Neutral Sentiment',
    score: '0.52',
    timeAgo: '5 hours ago',
    sentimentClass: 'text-on-tertiary-fixed-variant',
    borderClass: 'border-l-4 border-tertiary-fixed-dim'
  },
  {
    id: 3,
    text: '"Still struggling with aggression towards the cat. We\'ve tried the suggested training but no improvement yet."',
    sentimentLabel: 'Negative Sentiment',
    score: '0.18',
    timeAgo: '1 day ago',
    sentimentClass: 'text-error',
    borderClass: 'border-l-4 border-error'
  }
])

const recentSearches = ref(['Sarah Jenkins', 'Pet #8812', 'Ref: AD-9021'])

// Methods
const retrainModel = () => {
  console.log('Retraining post-adoption risk model...')
}
</script>

<style scoped>
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>