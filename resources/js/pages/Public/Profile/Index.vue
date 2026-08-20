<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { 
    Award, 
    BookOpen, 
    ChevronRight, 
    FileText, 
    GraduationCap, 
    IdCard, 
    MapPin, 
    ShieldCheck, 
    Sparkles, 
    Target, 
    User, 
    Users 
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import PageBanner from '@/components/public/PageBanner.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { PaginatedData } from '@/types/pagination';
import type { VillagePage } from '@/types/village-page';

interface Mission {
    id: number;
    statement: string;
}

interface VillageProfile {
    village_name: string;
    village_code?: string;
    district?: string;
    regency?: string;
    province?: string;
    postal_code?: string;
    address?: string;
    phone?: string;
    email?: string;
    history?: string;
    vision?: string;
    area_hectares?: string;
    established_year?: number;
    logo_path?: string;
    logo_url?: string;
    missions?: Mission[];
}

interface Position {
    id: number;
    name: string;
    order: number;
}

interface ApparatusItem {
    id: number;
    ulid: string;
    name: string;
    nip?: string | null;
    education?: string | null;
    photo_path?: string | null;
    photo_url?: string | null;
    is_active: boolean;
    position?: Position | null;
}

const props = defineProps<{
    pages: PaginatedData<VillagePage>;
    villageProfile?: VillageProfile | null;
    apparatuses?: ApparatusItem[];
}>();

// Filter for Organization Structure
const selectedCategory = ref<string>('all');

const uniquePositions = computed(() => {
    if (!props.apparatuses) return [];
    const positionsMap = new Map<number, Position>();
    props.apparatuses.forEach(a => {
        if (a.position) {
            positionsMap.set(a.position.id, a.position);
        }
    });
    return Array.from(positionsMap.values()).sort((a, b) => a.order - b.order);
});

const filteredApparatuses = computed(() => {
    if (!props.apparatuses) return [];
    if (selectedCategory.value === 'all') {
        return props.apparatuses;
    }
    return props.apparatuses.filter(a => a.position?.id.toString() === selectedCategory.value);
});

// Separate leader (Kepala Desa) if exists for hierarchy spotlight
const villageHead = computed(() => {
    return props.apparatuses?.find(a => 
        a.position?.name?.toLowerCase().includes('kepala desa') || 
        a.position?.name?.toLowerCase().includes('kades')
    );
});
</script>

<template>
    <PublicLayout>
        <Head :title="`Profil & Struktur Organisasi Desa ${villageProfile?.village_name || 'Burujuljaya'}`" />

        <!-- Header Hero Banner -->
        <template #banner>
            <PageBanner 
                pageKey="profile" 
                title="Profil & Struktur Organisasi" 
                :subtitle="`Mengenal sejarah, visi-misi, dan susunan aparatur pemerintah Desa ${villageProfile?.village_name || 'Burujuljaya'}.`"
            />
        </template>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">
            
            <!-- 1. Sejarah & Identitas Desa -->
            <section class="bg-white dark:bg-gray-900 rounded-3xl p-6 sm:p-10 border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    <div class="lg:col-span-4 flex flex-col items-center justify-center p-6 bg-emerald-50/60 dark:bg-emerald-950/20 rounded-2xl border border-emerald-100 dark:border-emerald-900/40 text-center">
                        <img 
                            v-if="villageProfile?.logo_url" 
                            :src="villageProfile.logo_url" 
                            alt="Logo Desa" 
                            class="w-32 h-32 object-contain mb-4"
                        />
                        <div v-else class="w-24 h-24 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 font-extrabold text-2xl flex items-center justify-center mb-3">
                            DB
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Desa {{ villageProfile?.village_name || 'Burujuljaya' }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ villageProfile?.district ? `Kec. ${villageProfile.district}, ` : '' }}{{ villageProfile?.regency || 'Kab. Tasikmalaya' }}
                        </p>
                        
                        <div class="w-full grid grid-cols-2 gap-2 mt-6 pt-6 border-t border-emerald-200/50 dark:border-emerald-800/40 text-left text-xs">
                            <div>
                                <span class="text-gray-400">Kode Desa:</span>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ villageProfile?.village_code || '32.06.xx' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-400">Kode Pos:</span>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ villageProfile?.postal_code || '46194' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-8 space-y-4">
                        <div class="inline-flex items-center gap-2 text-emerald-600 dark:text-emerald-400 text-xs font-bold uppercase tracking-wider">
                            <BookOpen class="w-4 h-4" />
                            <span>Sejarah Desa</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">
                            Asal Usul & Kilas Sejarah
                        </h2>
                        <div 
                            v-if="villageProfile?.history" 
                            class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 text-sm sm:text-base leading-relaxed" 
                            v-html="villageProfile.history"
                        ></div>
                        <p v-else class="text-gray-500 italic text-sm">
                            Informasi sejarah desa belum diperbarui.
                        </p>
                    </div>
                </div>
            </section>

            <!-- 2. Visi & Misi Desa -->
            <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Visi -->
                <div class="bg-gradient-to-br from-emerald-600 to-teal-700 text-white rounded-3xl p-8 shadow-lg flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold backdrop-blur-md mb-6">
                            <Target class="w-3.5 h-3.5 text-emerald-200" />
                            <span>Visi Desa</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Visi Pembangunan</h3>
                        <blockquote class="text-lg sm:text-xl font-medium leading-relaxed italic text-emerald-50">
                            "{{ villageProfile?.vision || 'Mewujudkan Desa Burujuljaya yang Maju, Mandiri, Berakhlak, dan Sejahtera Berkelanjutan.' }}"
                        </blockquote>
                    </div>
                    <div class="pt-6 mt-6 border-t border-white/20 text-xs text-emerald-100">
                        Pemerintah Desa {{ villageProfile?.village_name || 'Burujuljaya' }}
                    </div>
                </div>

                <!-- Misi -->
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 text-xs font-semibold mb-6">
                            <Award class="w-3.5 h-3.5" />
                            <span>Misi Desa</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Misi Pembangunan</h3>
                        
                        <ul v-if="villageProfile?.missions && villageProfile.missions.length > 0" class="space-y-3.5">
                            <li 
                                v-for="(mission, index) in villageProfile.missions" 
                                :key="mission.id" 
                                class="flex items-start gap-3 text-sm text-gray-600 dark:text-gray-300"
                            >
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-400 font-bold text-xs flex items-center justify-center mt-0.5">
                                    {{ index + 1 }}
                                </span>
                                <span class="leading-relaxed">{{ mission.statement }}</span>
                            </li>
                        </ul>
                        <div v-else class="text-gray-500 text-sm space-y-2">
                            <p>1. Meningkatkan kualitas pelayanan publik berbasis digital yang cepat dan transparan.</p>
                            <p>2. Mendorong pertumbuhan ekonomi desa melalui penguatan UMKM dan potensi lokal.</p>
                            <p>3. Meningkatkan pembangunan infrastruktur desa yang merata dan berkelanjutan.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3. STRUKTUR ORGANISASI & PERANGKAT DESA -->
            <section class="space-y-8">
                <div class="text-center max-w-3xl mx-auto space-y-3">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300 text-xs font-semibold">
                        <Users class="w-4 h-4" />
                        <span>Pemerintahan Desa</span>
                    </div>
                    <h2 class="text-2xl sm:text-4xl font-extrabold text-gray-900 dark:text-white">
                        Struktur Organisasi & Perangkat Desa
                    </h2>
                    <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400">
                        Susunan aparatur dan pamong desa yang bertugas melayani masyarakat Desa {{ villageProfile?.village_name || 'Burujuljaya' }}.
                    </p>
                </div>

                <!-- Position Category Filters -->
                <div v-if="uniquePositions.length > 0" class="flex flex-wrap items-center justify-center gap-2 pt-2">
                    <button
                        @click="selectedCategory = 'all'"
                        :class="[
                            selectedCategory === 'all'
                                ? 'bg-emerald-600 text-white shadow-sm'
                                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700',
                            'px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all'
                        ]"
                    >
                        Semua Aparatur ({{ apparatuses?.length || 0 }})
                    </button>

                    <button
                        v-for="pos in uniquePositions"
                        :key="pos.id"
                        @click="selectedCategory = pos.id.toString()"
                        :class="[
                            selectedCategory === pos.id.toString()
                                ? 'bg-emerald-600 text-white shadow-sm'
                                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700',
                            'px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all'
                        ]"
                    >
                        {{ pos.name }}
                    </button>
                </div>

                <!-- Apparatus Cards Grid -->
                <div v-if="filteredApparatuses.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div 
                        v-for="person in filteredApparatuses" 
                        :key="person.id" 
                        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col group"
                    >
                        <!-- Photo -->
                        <div class="h-64 relative bg-gray-100 dark:bg-gray-800 overflow-hidden">
                            <img 
                                v-if="person.photo_url || person.photo_path" 
                                :src="person.photo_url || ('/storage/' + person.photo_path)" 
                                :alt="person.name" 
                                class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500" 
                            />
                            <div v-else class="w-full h-full flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-400">
                                <User class="w-16 h-16 stroke-1 mb-2 text-gray-300 dark:text-gray-600" />
                                <span class="text-xs text-gray-400">Foto belum tersedia</span>
                            </div>

                            <!-- Position Badge Overlay -->
                            <div class="absolute bottom-3 inset-x-3">
                                <div class="bg-gray-900/85 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/10 text-center">
                                    <span class="text-xs font-bold text-emerald-400 line-clamp-1">
                                        {{ person.position?.name || 'Aparatur Desa' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="p-5 flex flex-col flex-1 text-center justify-between space-y-3">
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-base line-clamp-1">
                                    {{ person.name }}
                                </h4>
                                <div v-if="person.nip" class="flex items-center justify-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <IdCard class="w-3.5 h-3.5" />
                                    <span>NIP: {{ person.nip }}</span>
                                </div>
                            </div>

                            <div v-if="person.education" class="pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                <GraduationCap class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" />
                                <span>Pendidikan: {{ person.education }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State for Apparatus -->
                <div v-else class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-12 text-center text-gray-400 max-w-lg mx-auto">
                    <Users class="w-12 h-12 mx-auto mb-3 stroke-1 text-gray-300 dark:text-gray-600" />
                    <h4 class="font-bold text-gray-900 dark:text-white text-base">Belum Ada Data Perangkat Desa</h4>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Data aparatur desa dapat ditambahkan dan diperbarui melalui menu Perangkat Desa di Panel Admin.
                    </p>
                </div>
            </section>

            <!-- 4. Halaman Informasi Tambahan (CMS) -->
            <section v-if="pages.data.length > 0" class="space-y-6 pt-8 border-t border-gray-200 dark:border-gray-800">
                <div class="text-center max-w-2xl mx-auto space-y-2">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Dokumen & Artikel Profil Lainnya</h3>
                    <p class="text-xs sm:text-sm text-gray-500">Kumpulan informasi dan dokumen pelengkap profil desa.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div 
                        v-for="page in pages.data" 
                        :key="page.id" 
                        class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col group"
                    >
                        <div class="h-44 bg-gray-100 dark:bg-gray-800 relative overflow-hidden">
                            <img 
                                v-if="page.featured_image_url" 
                                :src="page.featured_image_url" 
                                :alt="page.title" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                <FileText class="w-10 h-10 stroke-1" />
                            </div>
                        </div>
                        <div class="p-5 flex flex-col flex-1">
                            <h4 class="font-bold text-gray-900 dark:text-white text-base mb-2 group-hover:text-emerald-600 transition-colors line-clamp-2">
                                <Link :href="route('profile.show', page.slug)">{{ page.title }}</Link>
                            </h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-4 flex-1">
                                {{ page.excerpt || 'Klik untuk membaca selengkapnya.' }}
                            </p>
                            <Link 
                                :href="route('profile.show', page.slug)" 
                                class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 mt-auto"
                            >
                                <span>Baca Selengkapnya</span>
                                <ChevronRight class="w-3.5 h-3.5" />
                            </Link>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </PublicLayout>
</template>
