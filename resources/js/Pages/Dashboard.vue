<template>
  <div class="bg-background text-on-surface">
    <!-- Left Side Navigation -->
    <aside class="fixed left-0 top-0 h-full w-[260px] bg-inverse-surface dark:bg-surface-container-highest flex flex-col py-6 space-y-2 shadow-lg z-50">
      <div class="px-6 mb-8">
        <h1 class="text-headline-md font-headline-md font-bold text-inverse-on-surface">Pawfect</h1>
        <p class="font-label-md text-label-md text-surface-variant opacity-80">AI Admin Portal</p>
      </div>
      <nav class="flex-1 overflow-y-auto custom-scrollbar">
        <a v-for="item in navItems" :key="item.label"
           class="flex items-center gap-3 px-4 py-3 mx-2 rounded-lg transition-colors"
           :class="[item.active ? 'bg-primary-container text-on-primary-container' : 'text-surface-variant hover:bg-on-surface-variant/10']"
           href="#">
          <span class="material-symbols-outlined">{{ item.icon }}</span>
          <span class="font-label-md">{{ item.label }}</span>
        </a>
      </nav>
      <div class="px-6 pt-6 mt-4 border-t border-surface-variant/20">
        <button class="w-full py-2 bg-primary text-white font-label-md rounded-lg transition-all hover:bg-primary-container active:scale-95">
          View Platform
        </button>
      </div>
    </aside>

    <!-- Top App Bar -->
    <header class="fixed top-0 right-0 z-40 h-[72px] flex justify-between items-center w-[calc(100%-260px)] ml-[260px] px-gutter bg-surface/80 backdrop-blur-md border-b border-outline-variant/30">
      <div class="flex items-center gap-6 flex-1">
        <div class="relative w-full max-w-md group">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
          <input v-model="searchQuery"
                 class="w-full h-11 pl-10 pr-4 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all font-body-sm outline-none"
                 placeholder="Search pets, breeds, or IDs..." type="text" />
        </div>
      </div>
      <div class="flex items-center gap-4">
        <button class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container-high transition-colors text-on-surface-variant">
          <span class="material-symbols-outlined">notifications</span>
        </button>
        <button class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container-high transition-colors text-on-surface-variant">
          <span class="material-symbols-outlined">settings</span>
        </button>
        <button class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container-high transition-colors text-on-surface-variant">
          <span class="material-symbols-outlined">help_outline</span>
        </button>
        <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center overflow-hidden border border-outline-variant">
          <img alt="Admin User Profile" class="w-full h-full object-cover" :src="userAvatar" />
        </div>
      </div>
    </header>

    <!-- Main Content Canvas -->
    <main class="ml-[260px] pt-[72px] min-h-screen">
      <!-- Sticky Filter & Stats Section -->
      <div class="sticky top-[72px] z-30 bg-surface/95 backdrop-blur-sm px-gutter py-6 border-b border-outline-variant/20">
        <div class="flex flex-col gap-6 max-w-container-max mx-auto">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
              <h2 class="font-headline-md text-headline-md text-on-surface">Pet Listings</h2>
              <p class="font-body-sm text-on-surface-variant">Manage and monitor pet inventory across all shelters.</p>
            </div>
            <div class="flex items-center gap-3">
              <div class="flex bg-surface-container rounded-lg p-1 border border-outline-variant/50">
                <button v-for="filter in speciesFilters" :key="filter"
                        class="px-3 py-1.5 rounded-md transition-all font-label-md"
                        :class="selectedSpecies === filter ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant hover:text-on-surface'"
                        @click="selectedSpecies = filter">
                  {{ filter }}
                </button>
              </div>
              <button class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-label-md shadow-sm hover:shadow-md transition-all active:scale-95">
                <span class="material-symbols-outlined text-[20px]">add</span>
                Add New Pet
              </button>
            </div>
          </div>

          <!-- Stats Bar -->
          <div class="flex flex-wrap items-center gap-8 py-4 px-6 bg-surface-container-low rounded-2xl border border-outline-variant/30">
            <div class="flex items-center gap-2">
              <span class="font-label-md text-on-surface-variant">Showing</span>
              <span class="font-headline-sm text-headline-sm text-primary">{{ filteredPets.length }}</span>
              <span class="font-label-md text-on-surface-variant">pets</span>
            </div>
            <div class="h-6 w-px bg-outline-variant"></div>
            <div class="flex items-center gap-2">
              <div class="w-2 h-2 rounded-full bg-secondary"></div>
              <span class="font-label-md text-on-surface-variant">{{ statusCounts.available }} Available</span>
            </div>
            <div class="flex items-center gap-2">
              <div class="w-2 h-2 rounded-full bg-outline"></div>
              <span class="font-label-md text-on-surface-variant">{{ statusCounts.adopted }} Adopted</span>
            </div>
            <div class="flex items-center gap-2">
              <div class="w-2 h-2 rounded-full bg-error"></div>
              <span class="font-label-md text-tertiary font-bold">{{ statusCounts.urgent }} Urgent</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Pet Grid Content -->
      <div class="px-gutter py-8 max-w-container-max mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-gutter">
          <div v-for="pet in paginatedPets" :key="pet.id"
               class="group bg-surface-container-lowest rounded-2xl border border-outline-variant/50 overflow-hidden shadow-sm hover:shadow-lg hover:border-primary transition-all duration-300"
               :class="{ 'grayscale opacity-75': pet.status === 'Adopted' }">
            <div class="relative aspect-[4/3] overflow-hidden">
              <img :src="pet.image" :alt="pet.name" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
              <div class="absolute top-3 left-3 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider"
                   :class="pet.status === 'Urgent' ? 'bg-tertiary text-white' : (pet.status === 'Adopted' ? 'bg-on-surface/70 text-white' : 'bg-secondary text-white')">
                {{ pet.status }}
              </div>
              <div v-if="pet.matchScore" class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-md px-2 py-1 rounded-lg text-primary flex items-center gap-1 font-label-sm shadow-sm">
                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">psychology</span>
                {{ pet.matchScore }}% Match
              </div>
            </div>
            <div class="p-5">
              <div class="flex justify-between items-start mb-2">
                <div>
                  <h3 class="font-headline-sm text-headline-sm text-on-surface">{{ pet.name }}</h3>
                  <p class="font-body-sm text-on-surface-variant">{{ pet.breed }} • ID: {{ pet.id }}</p>
                </div>
              </div>
              <div class="flex flex-wrap gap-2 mb-6">
                <span class="px-2 py-1 bg-surface-container rounded-md text-[11px] font-label-md text-on-surface-variant">{{ pet.age }}</span>
                <span class="px-2 py-1 bg-surface-container rounded-md text-[11px] font-label-md text-on-surface-variant">{{ pet.size }}</span>
                <span class="px-2 py-1 bg-surface-container rounded-md text-[11px] font-label-md text-on-surface-variant">{{ pet.gender }}</span>
              </div>
              <div class="flex gap-2">
                <button class="flex-1 py-2 rounded-lg border border-outline-variant font-label-md text-on-surface hover:bg-surface-container-high transition-colors">Edit</button>
                <button v-if="pet.status !== 'Adopted'" class="flex-[2] py-2 rounded-lg bg-secondary text-white font-label-md hover:bg-primary transition-colors">
                  View Apps ({{ pet.applications }})
                </button>
                <button v-else class="w-full py-2 rounded-lg border border-outline-variant font-label-md text-on-surface bg-surface-container-high">
                  View Profile
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination Controls -->
        <div class="mt-12 flex items-center justify-between py-6 border-t border-outline-variant/30">
          <span class="font-body-sm text-on-surface-variant">Showing {{ startIndex + 1 }} to {{ endIndex }} of {{ filteredPets.length }} pets</span>
          <div class="flex items-center gap-2">
            <button :disabled="currentPage === 1" @click="currentPage--"
                    class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors disabled:opacity-30">
              <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button v-for="page in displayedPages" :key="page"
                    class="w-10 h-10 flex items-center justify-center rounded-lg transition-colors font-label-md"
                    :class="currentPage === page ? 'bg-primary text-white' : 'border border-outline-variant text-on-surface-variant hover:bg-surface-container'"
                    @click="currentPage = page">
              {{ page }}
            </button>
            <button :disabled="currentPage === totalPages" @click="currentPage++"
                    class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors disabled:opacity-30">
              <span class="material-symbols-outlined">chevron_right</span>
            </button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

// Navigation items
const navItems = ref([
  { label: 'Overview', icon: 'dashboard', active: false },
  { label: 'Pets', icon: 'pets', active: true },
  { label: 'Adoptions', icon: 'favorite', active: false },
  { label: 'Shop', icon: 'shopping_bag', active: false },
  { label: 'Orders', icon: 'receipt_long', active: false },
  { label: 'Users', icon: 'group', active: false },
  { label: 'Matching', icon: 'psychology', active: false },
  { label: 'Analytics', icon: 'analytics', active: false },
  { label: 'ML Insights', icon: 'insights', active: false }
])

const userAvatar = 'https://lh3.googleusercontent.com/aida-public/AB6AXuCw-VY8m-MF9SjiJFadnubeko1x7lsBfm1gF8AmUlqodUIaBSFtNoQ4AuUSQyx4UQklYCe9QvQe_89R5CxofDGUnl5NK8Eu_Zxo8wFPukxf1Vje7sHAunCAXRBoprZ1kWSy7ziu0XsWgC7xe-ui7iuUYzkggL4paXX2752ORh39y1uFza0NNSlJPDxhk0-chixjeTBIjHYgClay1nJVr2QOsboVyQwKFRTSNHNalLe7j7KK7YCGT1IiHl_Y9D6NaGNrSIqTl8TNdWQ'

// Search and filters
const searchQuery = ref('')
const selectedSpecies = ref('All Species')
const speciesFilters = ref(['All Species', 'Dogs', 'Cats', 'Exotics'])

// Pagination
const currentPage = ref(1)
const itemsPerPage = 8

// Pet data
const pets = ref([
  {
    id: 'BF-402',
    name: 'Cooper',
    breed: 'Beagle Mix',
    age: '2 years',
    size: 'Medium',
    gender: 'Male',
    status: 'Urgent',
    matchScore: 98,
    applications: 5,
    species: 'Dogs',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAsQbK1UYgUOgjqE48LUP6RxF_ICYMTHI0LyEXE7EeUj7AIVii2a4eEjhzXlYC5D_fIfrAb8KnZaYQjes6Fua9AyL52SuvdOQn6zeDwa-9T3GU5DcfpYeuK_RxwaQWHDLtm7AjZST2zR_LiwQeUbyIRLKCQuQyn4NsJo39MD1MMLLENRbeYbVfOzEITnnQTtomDZLqo3ce3jd4RxSEwIat9ybtQXd_foatyRhPhURC_alOy69SpTYAV0tLgWGVlNAF5PyEhxU-0Q5c'
  },
  {
    id: 'CS-881',
    name: 'Luna',
    breed: 'Domestic Shorthair',
    age: '4 months',
    size: 'Small',
    gender: 'Female',
    status: 'Available',
    matchScore: null,
    applications: 12,
    species: 'Cats',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDIdA54l50raHMSuSzCJCCSWhshdPusY5mkvilkPX5BS9Vy9ZQz0ChZJIIw3Zb-yI32JDkMo0m3uLPmpPUz9C-zN_XXWclQTgch87lqwau8dLUoa5Z44Wv-Rv_mjK0fkMYL3E-I_Yb505eE5KrIH6LwPjGjKmsGSeE6ELoGDCFQX-eRE8fyTWOtRmr55sjwIaQiZavrcBP63iA21tsURUtwmtBb4BmuvwHxFsh7bLfXugYQwDu_UTI9ko6rL6BeNF7gLx4bHF5FP58'
  },
  {
    id: 'GR-122',
    name: 'Bear',
    breed: 'Golden Retriever',
    age: '5 years',
    size: 'Large',
    gender: 'Male',
    status: 'Available',
    matchScore: 85,
    applications: 3,
    species: 'Dogs',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAa500BwUBWmZ8gFFnudDPIJv_h_uvWqpN0b_qAQhsd5fyBtukBG6MhHbkZfgfyJGrlAaJpNUF39VpAdANIgaYHhba9IV0ZOd4wQCuZNOrb--bmcHPl0zNVYDpQqjHqF3atcuiDBVMYEjkXSK--SlL_ch02NDmHS6nFb-6Vnm8WAjHaW0DQbGpeuyecTEyOsVtNxbZbDAWMBTG2R6MYkwnhQFl_MSlHLJd_2fb871b63vrDdNukjt9bkroIeCZEBwEh0UgLltgplUI'
  },
  {
    id: 'CS-772',
    name: 'Milo',
    breed: 'Ginger Tabby',
    age: '3 months',
    size: 'Small',
    gender: 'Male',
    status: 'Adopted',
    matchScore: null,
    applications: 0,
    species: 'Cats',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDPLzpPdsQGED6ofIsIVOW_EudakCrXmPavLVjJnIY9Uv0W8aEXL0SGeBp5zHKxr4ShzM_xgZcZwyCPL8YM4X31VNPFMZhJ74G_asa4wY01wddGwW6lRpcdaUXNAP8NFIMOftaGeW0lu_43BjtlBSi90LTmxACd_T04QbgFE1Jzi6hwbwbTK7eLwNgTJlmotBwE6QnVOZHAzLsc2g1wXxrQPU0ggrh2X6AUpCfBf8tFdP7kCu8WNgP7Nrcvm7Glcac0iqm-9d_5VEg'
  },
  {
    id: 'PM-092',
    name: 'Daisy',
    breed: 'Pomeranian',
    age: 'Senior',
    size: 'Extra Small',
    gender: 'Female',
    status: 'Available',
    matchScore: null,
    applications: 8,
    species: 'Dogs',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBx1q_MXbPWJ34ibE_eXVS7nOoKItHrCtnr5fLGv8idBO0kvA_4i_stNSismOM-MwNsxT5lUq8VakXe9ZvYoOttoJSt8WRZLZEXYcP6Ibz9CpoKBDO8sjwkcIetieAP2DbomQY7JkV2PjCcgNEunixkezcnHdMRm4tOSUB9xnlSk-M9wbXG_33Zm3sCfwC68BiY_WT-KqCf-3ELlbfvri-SrANkiSAd1mX8GBYzxs56FEuS9INjDCXF0nxq4ld9zmN2Hv6t0hMQkOA'
  },
  {
    id: 'CS-441',
    name: 'Blue',
    breed: 'Siamese',
    age: '1 year',
    size: 'Medium',
    gender: 'Male',
    status: 'Available',
    matchScore: 92,
    applications: 14,
    species: 'Cats',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBUxE4E-73qMxMALHty7oxBhbKsni8twtD8bdbAi2It44likVNW9xLHWJ3aoRQgcxwMRBccwFmHTaKBZilPUY4-9sUVNAZjR4O9MpwzZ1pGZOc-T3NsZ0IbjnHCk0KeR1S3xw46ez00BaDWVmxM4XhakqhW_pzyEt_aQIGuFcEqlk6CWfGT4DhRk9yW8kWk3N0x6DZLtE_cZWLnfhkku9yLi5P2CSD-N4ZuLNGvuXXNS2Pz_Zous_4wscX8CypwLgNJ2Ltum6PQmhs'
  },
  {
    id: 'GS-909',
    name: 'Shadow',
    breed: 'German Shepherd',
    age: '3 years',
    size: 'Extra Large',
    gender: 'Male',
    status: 'Urgent',
    matchScore: null,
    applications: 2,
    species: 'Dogs',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuC-EKWfqlFZxm3UnZh4j6CHpbBF3GMO4DjZuvmlR-ShJu7RKT3alNWYziJWLQ_cDNUMsQbNKxu2AcezzliSUyiQWRQal-Df6SR2_ws44ZidjrfiCxR_Va2ggWLfzmdI07d5Uv02qtssLEuHygkm1XsTChkDnvXp18qBd0en68BO2ENqnr9g3ZXCkcMVIxcYHrNJ76eJKRIG6zmOQw7-6VpmlXXjDB5cuv7UZwwlbKj1hQ78BL15NH0hKWzRikw7ayvQuNzaKliXqZM'
  },
  {
    id: 'RB-004',
    name: 'Pippin',
    breed: 'Holland Lop',
    age: '1 year',
    size: 'Small',
    gender: 'Male',
    status: 'Available',
    matchScore: 78,
    applications: 6,
    species: 'Exotics',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuB8aLsfbwlENx3Fk1eGUxyaLlcS4kuukb7AQG_TH-06VhExswh8BlLkxf7BMFpe2eSK67731ZMTBSffMbsfbB2b6ZQbu9lOCoHZdz1rOOdqjdWgIjTuzAUs1xaPzOuh5u5zgZNqrnlyWuN8R6nRoy4alWXh22KPyc98FVfMVlsjJ1rXcg39xpAdmXqeAyHQui85_SRX7m3Y6ZkMlRDb2UHCre5stq2bpW_og5MyKgUZjb9zwhVHUtszeM4Ek_1taKoH3EjIW5c6Fjw'
  }
])

// Computed: filtered pets based on search and species
const filteredPets = computed(() => {
  let result = pets.value

  if (selectedSpecies.value !== 'All Species') {
    result = result.filter(pet => pet.species === selectedSpecies.value)
  }

  if (searchQuery.value.trim()) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(pet =>
      pet.name.toLowerCase().includes(query) ||
      pet.breed.toLowerCase().includes(query) ||
      pet.id.toLowerCase().includes(query)
    )
  }

  return result
})

// Status counts
const statusCounts = computed(() => {
  const available = filteredPets.value.filter(p => p.status === 'Available').length
  const adopted = filteredPets.value.filter(p => p.status === 'Adopted').length
  const urgent = filteredPets.value.filter(p => p.status === 'Urgent').length
  return { available, adopted, urgent }
})

// Pagination computed
const totalPages = computed(() => Math.ceil(filteredPets.value.length / itemsPerPage))
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage)
const endIndex = computed(() => Math.min(startIndex.value + itemsPerPage, filteredPets.value.length))
const paginatedPets = computed(() => filteredPets.value.slice(startIndex.value, endIndex.value))

// Displayed page numbers
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

// Reset page when filters change
const resetPage = () => {
  currentPage.value = 1
}

// Watch for filter changes to reset pagination
import { watch } from 'vue'
watch([searchQuery, selectedSpecies], resetPage)
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #bccac1; border-radius: 10px; }

.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>