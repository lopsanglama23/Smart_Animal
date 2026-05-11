<template>
  <div class="bg-background text-on-surface">
    <!-- SideNavBar -->
    <aside class="fixed left-0 top-0 h-full w-[260px] bg-inverse-surface dark:bg-surface-container-highest flex flex-col py-6 space-y-2 shadow-lg z-50">
      <div class="px-6 mb-8">
        <h1 class="text-headline-md font-headline-md font-bold text-inverse-on-surface">Pawfect</h1>
        <p class="text-label-sm font-label-sm text-surface-variant/70 uppercase tracking-widest">AI Admin Portal</p>
      </div>
      <nav class="flex-1 space-y-1">
        <a v-for="item in navItems" :key="item.label"
           class="flex items-center gap-3 px-4 py-3 rounded-lg mx-2 transition-colors"
           :class="item.active ? 'bg-primary-container text-on-primary-container' : 'text-surface-variant hover:bg-on-surface-variant/10'"
           href="#">
          <span class="material-symbols-outlined">{{ item.icon }}</span>
          <span class="font-label-md text-label-md">{{ item.label }}</span>
        </a>
      </nav>
      <div class="px-4 mt-auto">
        <button class="w-full py-3 bg-primary text-on-primary rounded-lg font-label-md text-label-md hover:opacity-90 transition-all">
          View Platform
        </button>
      </div>
    </aside>

    <!-- TopAppBar -->
    <header class="fixed top-0 right-0 z-40 h-[72px] flex justify-between items-center w-[calc(100%-260px)] ml-[260px] px-gutter bg-surface/80 backdrop-blur-md border-b border-outline-variant/30">
      <div class="flex items-center flex-1 max-w-xl">
        <div class="relative w-full">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
          <input v-model="searchQuery"
                 class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded-full text-body-sm font-body-sm focus:outline-none focus:border-primary"
                 placeholder="Search applications, pets, or applicants..." type="text" />
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
        <div class="w-10 h-10 rounded-full overflow-hidden border border-outline-variant">
          <img :src="userAvatar" alt="Admin User Profile" class="w-full h-full object-cover" />
        </div>
      </div>
    </header>

    <!-- Main Content Canvas -->
    <main class="pt-[72px] ml-[260px] p-gutter min-h-screen">
      <!-- Summary Bar -->
      <section class="mb-lg">
        <div class="flex items-center justify-between mb-sm">
          <h2 class="font-headline-md text-headline-md text-on-surface">Adoptions Management</h2>
          <button class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md text-label-md hover:opacity-90 shadow-sm transition-all">
            <span class="material-symbols-outlined">add</span>
            New Application
          </button>
        </div>
        <div class="flex flex-wrap gap-3">
          <StatusBadge :count="totalApplications" label="Total" colorClass="surface-container-highest" />
          <StatusBadge :count="statusCounts.pending" label="Pending" colorClass="secondary-container" icon="pending_actions" :iconFill="1" />
          <StatusBadge :count="statusCounts.approved" label="Approved" colorClass="primary-container" icon="check_circle" :iconFill="1" />
          <StatusBadge :count="statusCounts.rejected" label="Rejected" colorClass="error-container" icon="cancel" />
        </div>
      </section>

      <!-- Data Table Section -->
      <section class="bg-surface-container-lowest rounded-xl custom-shadow overflow-hidden border border-outline-variant/30">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-surface-container-low/50">
                <th v-for="col in tableColumns" :key="col.key" class="px-6 py-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">
                  {{ col.label }}
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
              <template v-for="app in filteredApplications" :key="app.id">
                <!-- Main row -->
                <tr class="hover:bg-surface-container transition-colors cursor-pointer group" @click="toggleExpand(app.id)">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <img :src="app.applicantAvatar" :alt="app.applicantName" class="w-10 h-10 rounded-full object-cover border border-outline-variant" />
                      <div>
                        <div class="font-label-md text-label-md text-on-surface">{{ app.applicantName }}</div>
                        <div class="text-label-sm text-on-surface-variant">{{ app.applicantEmail }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                      <div class="w-8 h-8 rounded-lg overflow-hidden">
                        <img :src="app.petImage" :alt="app.petName" class="w-full h-full object-cover" />
                      </div>
                      <span class="font-body-md text-body-md">{{ app.petName }}</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 font-body-sm text-body-sm text-on-surface-variant">{{ app.appliedDate }}</td>
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-1.5" :class="homeCheckClass(app.homeCheck)">
                      <span class="material-symbols-outlined text-[18px]" :style="{ fontVariationSettings: homeCheckIconFill(app.homeCheck) }">{{ homeCheckIcon(app.homeCheck) }}</span>
                      <span class="font-label-sm text-label-sm">{{ app.homeCheck }}</span>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <MatchScore :score="app.matchScore" :label="app.matchLabel" />
                  </td>
                  <td class="px-6 py-4">
                    <StatusChip :status="app.status" />
                  </td>
                  <td class="px-6 py-4 text-right">
                    <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">
                      {{ expandedRow === app.id ? 'expand_less' : 'expand_more' }}
                    </span>
                  </td>
                </tr>

                <!-- Expanded AI Explanation Row -->
                <tr v-if="expandedRow === app.id" class="bg-surface-container-low/30 border-l-4 border-primary">
                  <td class="px-12 py-6" colspan="7">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                      <div>
                        <h4 class="font-label-md text-label-md text-primary mb-3 flex items-center gap-2">
                          <span class="material-symbols-outlined text-[18px]">psychology</span>
                          AI Match Explanation
                        </h4>
                        <p class="text-body-sm text-on-surface-variant leading-relaxed">
                          {{ app.aiExplanation }}
                        </p>
                      </div>
                      <div class="flex flex-wrap gap-4">
                        <div class="flex-1 bg-surface-container-lowest p-4 rounded-lg border border-outline-variant/50">
                          <span class="text-label-sm font-label-sm text-on-surface-variant block mb-1">Living Situation</span>
                          <span class="font-body-md text-body-md text-on-surface">{{ app.livingSituation }}</span>
                        </div>
                        <div class="flex-1 bg-surface-container-lowest p-4 rounded-lg border border-outline-variant/50">
                          <span class="text-label-sm font-label-sm text-on-surface-variant block mb-1">Household</span>
                          <span class="font-body-md text-body-md text-on-surface">{{ app.household }}</span>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>

        <!-- Table Pagination/Footer -->
        <div class="px-6 py-4 bg-surface-container-low/30 border-t border-outline-variant/20 flex items-center justify-between flex-wrap gap-4">
          <span class="text-label-sm font-label-sm text-on-surface-variant">Showing {{ startIndex + 1 }}-{{ endIndex }} of {{ filteredApplications.length }} applications</span>
          <div class="flex gap-2">
            <button :disabled="currentPage === 1" @click="currentPage--"
                    class="p-1 hover:bg-surface-container rounded transition-all disabled:opacity-30">
              <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button v-for="page in displayedPages" :key="page"
                    class="px-3 py-1 rounded text-label-sm font-label-sm transition-all"
                    :class="currentPage === page ? 'bg-primary text-on-primary' : 'hover:bg-surface-container'"
                    @click="currentPage = page">
              {{ page }}
            </button>
            <button :disabled="currentPage === totalPages" @click="currentPage++"
                    class="p-1 hover:bg-surface-container rounded transition-all disabled:opacity-30">
              <span class="material-symbols-outlined">chevron_right</span>
            </button>
          </div>
        </div>
      </section>

      <!-- Contextual Help / AI Insights Card -->
      <section class="mt-lg grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="col-span-2 bg-gradient-to-br from-primary-container/20 to-secondary-container/20 p-6 rounded-xl border border-primary/10">
          <div class="flex items-start gap-4">
            <div class="p-3 bg-white rounded-lg custom-shadow">
              <span class="material-symbols-outlined text-primary text-[32px]">insights</span>
            </div>
            <div>
              <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Automated Matching Insights</h3>
              <p class="text-body-md text-on-surface-variant mb-4">
                Our ML models have processed 12 new applications today. Based on historical successful adoptions, we recommend prioritizing the <b>Sarah Jenkins</b> application for final approval.
              </p>
              <div class="flex gap-4">
                <button class="px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md text-label-md hover:opacity-90 shadow-sm transition-all">
                  Generate Batch Report
                </button>
                <button class="px-4 py-2 border border-primary text-primary rounded-lg font-label-md text-label-md hover:bg-primary/5 transition-all">
                  View Parameters
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/30 custom-shadow">
          <h3 class="font-label-md text-label-md text-on-surface mb-4">Upcoming Home Checks</h3>
          <div class="space-y-4">
            <div v-for="check in upcomingHomeChecks" :key="check.name" class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-secondary"></span>
                <span class="font-body-sm text-body-sm">{{ check.name }}</span>
              </div>
              <span class="text-label-sm font-label-sm text-on-surface-variant">{{ check.time }}</span>
            </div>
          </div>
          <button class="w-full mt-6 py-2 text-primary font-label-md text-label-md border-t border-outline-variant/20 pt-4 hover:underline">
            View Calendar
          </button>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import StatusBadge from './components/StatusBadge.vue'
import MatchScore from './components/MatchScore.vue'
import StatusChip from './components/StatusChip.vue'

// Navigation items
const navItems = ref([
  { label: 'Overview', icon: 'dashboard', active: false },
  { label: 'Pets', icon: 'pets', active: false },
  { label: 'Adoptions', icon: 'favorite', active: true },
  { label: 'Shop', icon: 'shopping_bag', active: false },
  { label: 'Orders', icon: 'receipt_long', active: false },
  { label: 'Users', icon: 'group', active: false },
  { label: 'Matching', icon: 'psychology', active: false },
  { label: 'Analytics', icon: 'analytics', active: false },
  { label: 'ML Insights', icon: 'insights', active: false }
])

const userAvatar = 'https://lh3.googleusercontent.com/aida-public/AB6AXuBjbfKyyhuGNjNDorgKiArol03r2ZrJKJvKyvY5pIfa7dklHjWKqY-JPL2L1BrZEiqhTn-o4gn1KXArwFC8lqyVB0onJ5NfxkTZy_En8IZg7DykG0YThHyvJMKOBkbOSnl0AqlCwy0ya-TEuZHfxTddvU0CvuzKpdb-z51U4_VIRwFBNQe_KXfmdM7J49l5RzyABuAbclSot50mukJJtLIdK_9o3kUdcCvoQ93BfFcOrNc7NelZ1hbxzNE32aSjNmKh94ggM00m_Ww'

// State
const searchQuery = ref('')
const currentPage = ref(1)
const itemsPerPage = 10
const expandedRow = ref(null)

// Table columns
const tableColumns = [
  { key: 'applicant', label: 'Applicant' },
  { key: 'pet', label: 'Pet' },
  { key: 'appliedDate', label: 'Applied Date' },
  { key: 'homeCheck', label: 'Home Check' },
  { key: 'matchScore', label: 'Match Score' },
  { key: 'status', label: 'Status' },
  { key: 'actions', label: '' }
]

// Applications data
const applications = ref([
  {
    id: 1,
    applicantName: 'Sarah Jenkins',
    applicantEmail: 'sarah.j@email.com',
    applicantAvatar: 'https://lh3.googleusercontent.com/aida-public/AB6AXuC9wyQesH4wKmLxJjj4VsSmyVeDUxrm99DzrjeHw29LaUWrLUJRGwtpp6Wb3YiGdQRQhJePTjG5AePpibOC0_-geIfmg3RpX4nUavjnfB3il-gMq_VWNUMbVOlFTURjvGufp0AnO-9BPUf-Eia6VIlIyiRZxuXefgX4ZY08vMHRboOq7-m4MegHKuuWxKCOhj8e7OZM2UPWjVRC-1PeqB2c6Xq9t2kAFuJbxbSb8rPk6xpDzQ0fafqoL1NvVSr_V-KCZoesykCLtpw',
    petName: 'Cooper',
    petImage: 'https://lh3.googleusercontent.com/aida-public/AB6AXuB2R8VK7rzGPUMZwj6zQJNoflR680XsJAdI3pj23D-wzuWbjL2Kn1d3BhuaU7A7zkO7caOUphnYk1IMtzUnH926ys3EXLEyKIP1RysKMzVL-kyWDp6kvcaGQjlGTH47UaWeDYIrh5ZjTosFNrwAKMnZ0yQfKaekHXPmBWv8FXZJ_sNEBjYeTcE5GbWaF7hlt8_NiSKJY_2sq018UzhMEIgE-0Fb0Zbm95qASzsVS_gRrRwh_L6KfHFVYBmYsWgmNQKW6kFwBgtZ-78',
    appliedDate: 'Oct 12, 2023',
    homeCheck: 'Passed',
    matchScore: 94,
    matchLabel: 'High Match',
    status: 'Approved',
    aiExplanation: "Sarah's active lifestyle and previous experience with large breeds make her an ideal match for Cooper. Our algorithm detected a strong compatibility between Cooper's high energy levels and Sarah's fenced 0.5-acre property. The AI also noted a preference for retrievers in her past adoption history.",
    livingSituation: 'Detached House, Large Yard',
    household: '2 Adults, No Kids'
  },
  {
    id: 2,
    applicantName: 'Marcus Chen',
    applicantEmail: 'm.chen@example.com',
    applicantAvatar: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAnTDZB2vZ6Zo-LyFUB1yOCf9p6j9z7NEXWZy6pIH4yMnG5yyuqk6ovnss9sHtUuC2t9FNrkchpMxfwVvkAfIDuoogH0dBjuVGI8oWIkl-lpKuol1m3MClQlfo691vUmKTRDXNpwhHETjXpqU_mUIf-0RYe2exqys9jxkLxqlrYQfBrll5fIycFSuEIltWJK--Ota3sPKqxOEWS4MUtl8q72G2J12zT7ppDEzb_ehS7HdniKf06NhCFXqKUsDxnbVst57f6vWzWmmE',
    petName: 'Luna',
    petImage: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAXFoXq1O6JJ0LBuk-_9wyDHw91-FDX5-OTFSv4qwUVSNPgBNG-27dbd_VgVyBtjfXaqT5CiVtPR-m0XGT_wMEKkOE3C6lMfH8Ti98PizMcZW589dZvMw1Vq6b0r-_MAMbU8WSbOIxN8jfXIv6RiRQuKi7tlTxe8xIdQPisJx7brZkfoRsBiMT3s2gvgEag2AcOmDsHZ1NWuwybB7uyLkLY7h0nhXPSWe6FnbvuIiTadcLfPnhWhsCBb1NZglDDMB4rI7tYCHEtdaQ',
    appliedDate: 'Oct 14, 2023',
    homeCheck: 'Scheduled',
    matchScore: 78,
    matchLabel: 'Strong',
    status: 'Pending',
    aiExplanation: "Marcus has a stable work-from-home schedule that aligns well with Luna's need for companionship. His previous experience with cats and the presence of a dedicated play area in his apartment suggest a smooth transition.",
    livingSituation: 'Apartment, 2-bedroom',
    household: 'Single Adult, No Kids'
  },
  {
    id: 3,
    applicantName: 'Elena Rodriguez',
    applicantEmail: 'elena.rod@webmail.com',
    applicantAvatar: 'https://lh3.googleusercontent.com/aida-public/AB6AXuADVr1vDuLGBP0IU3BgRUbrtPBextd457gWVTxg5q_ueiUhTHCXLOE_DlAZc3oLrMbPJnYqeC3P4oPPapTLtKZP0s1XiiJGiEMv9d3yRKQos6zShw-1sZ9RHL4sYMV1ZvASRunGPH9-fQm3Sd_pEyDNpfsI5Zu9CUWzCa55ajFtmHLd9d5kV18mnVl30LUFVSRhfFhkAzo4CMUFFfpTfleCgSyn8hWAoHHmeo9qiDQ1QXy6k0fEdWZ676-uWwHuIwrW-4IEeUi-5u8',
    petName: 'Bella',
    petImage: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBFW7uJ_RkHKoFfVgA1ROrcEn1Hy2cgiE3yD4BUNFMlIwDAI9s0O0nhZz7MeFOweafWN3turNBBL550w3Q88YDv9kBCaso1ma-yD8mscMrZp9Maw3CPjY2SAAsBGSeiVsHgS8cnxBdqa1PlvAJGTEBdQQVOCVlwACfU6OV25pO5YKKBHvo08GyjPnUDQpsrrDzBNmM-BrxDbPba-WoxkePVlXuky_GKFQNdNV0b1VgQ3MrCaBz-l6aq5Pj-dbDirGbFcEnYi3Y_uyA',
    appliedDate: 'Oct 15, 2023',
    homeCheck: 'Incomplete',
    matchScore: 45,
    matchLabel: 'Low Match',
    status: 'Rejected',
    aiExplanation: "Elena's application shows potential, but the home check was incomplete and her current living situation (apartment with breed restrictions) is not compatible with Bella's exercise needs.",
    livingSituation: 'Apartment, Small',
    household: '1 Adult, 1 Child'
  },
  {
    id: 4,
    applicantName: 'David Miller',
    applicantEmail: 'david.m88@email.com',
    applicantAvatar: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCLqlzfzLsyVqUZvx75A-33nu3htc4cQqTVUQEl1E2bZglrbd4jkZEBumMh5Xy-A_4eu759WBnQ1vTNj-33XIn1A-8pB5WRIyU25yAZDQvx1_VOSAywYe4rcqRlqpP6sdVm49547uRPXHLsWYR8lhNTeoCXO4HNZ3v8TroWoTYMQ_TDZq-uKwRJLyfeRXqdMCQ86KOcKMILXH74lrdOftYTwp7wK8EeKTqOj70yu3HJuQv_xiS3S0nf285xbYe5N6lCrm7Rbi8jPN8',
    petName: 'Oliver',
    petImage: 'https://lh3.googleusercontent.com/aida-public/AB6AXuD3hBVyxlhPr-klGqq5YViXkw2Lvy6oL2MUou7xfdMQJhc-L21AQzriiKzZ1F-FO4VQTAi8rM067bOnxz0a71n6hHKoPl-rQzH4rrfbPIyK_owx8pI_QtLFDTPCEVITyAKGy0GEzF-C6T3SjK8V9KqmnWmwZNduO8OiwlsC5N0Py6IlLiGIJB0N9Ch7epbZNrC5WY7Ubs-Tki9GqiAf5s8MN72p8JNAYqGhYuw7ldDxPAAMIrooOp_aWrDdTxjMKErw97WWH7wk9Ng',
    appliedDate: 'Oct 16, 2023',
    homeCheck: 'Passed',
    matchScore: 89,
    matchLabel: 'High Match',
    status: 'Approved',
    aiExplanation: "David's experience with senior cats and his quiet household environment make him an excellent match for Oliver. The home check confirmed a safe indoor setup with plenty of enrichment.",
    livingSituation: 'Townhouse, Fenced Yard',
    household: '2 Adults, No Kids'
  }
])

const upcomingHomeChecks = ref([
  { name: 'Marcus Chen', time: 'Today, 2:00 PM' },
  { name: 'Lisa Wong', time: 'Oct 19, 10:30 AM' },
  { name: 'Jim Parsons', time: 'Oct 20, 4:15 PM' }
])

// Computed values
const totalApplications = computed(() => applications.value.length)

const statusCounts = computed(() => {
  const counts = { pending: 0, approved: 0, rejected: 0 }
  applications.value.forEach(app => {
    if (app.status === 'Pending') counts.pending++
    else if (app.status === 'Approved') counts.approved++
    else if (app.status === 'Rejected') counts.rejected++
  })
  return counts
})

const filteredApplications = computed(() => {
  let result = applications.value
  if (searchQuery.value.trim()) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(app =>
      app.applicantName.toLowerCase().includes(query) ||
      app.applicantEmail.toLowerCase().includes(query) ||
      app.petName.toLowerCase().includes(query)
    )
  }
  return result
})

// Pagination
const totalPages = computed(() => Math.ceil(filteredApplications.value.length / itemsPerPage))
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage)
const endIndex = computed(() => Math.min(startIndex.value + itemsPerPage, filteredApplications.value.length))
const paginatedApplications = computed(() => filteredApplications.value.slice(startIndex.value, endIndex.value))

const displayedPages = computed(() => {
  const pages = []
  const maxVisible = 5
  let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2))
  let end = Math.min(totalPages.value, start + maxVisible - 1)
  if (end - start + 1 < maxVisible) {
    start = Math.max(1, end - maxVisible + 1)
  }
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  return pages
})

// Helper functions
const toggleExpand = (id) => {
  expandedRow.value = expandedRow.value === id ? null : id
}

const homeCheckClass = (status) => {
  if (status === 'Passed') return 'text-primary'
  if (status === 'Scheduled') return 'text-on-surface-variant/60'
  return 'text-error'
}

const homeCheckIcon = (status) => {
  if (status === 'Passed') return 'check_circle'
  if (status === 'Scheduled') return 'schedule'
  return 'error'
}

const homeCheckIconFill = (status) => {
  return status === 'Passed' ? "'FILL' 1" : "'FILL' 0"
}

// Reset page on search
import { watch } from 'vue'
watch(searchQuery, () => {
  currentPage.value = 1
  expandedRow.value = null
})
</script>

<style scoped>
.custom-shadow {
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
}

.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>