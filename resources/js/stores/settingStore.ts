import { defineStore } from 'pinia';
import { ref } from 'vue';
import type { VillageProfile, Setting } from '@/types';

export const useSettingStore = defineStore('setting', () => {
    const villageProfile = ref<VillageProfile | null>(null);
    const settings = ref<Setting[]>([]);
    const locale = ref('id');
    const theme = ref('default');

    const setVillageProfile = (profile: VillageProfile | null) => {
        villageProfile.value = profile;
    };

    const setSettings = (newSettings: Setting[]) => {
        settings.value = newSettings;
    };

    const getSetting = (key: string): string | null => {
        const setting = settings.value.find((s) => s.key === key);

        return setting ? setting.value : null;
    };

    const setLocale = (newLocale: string) => {
        locale.value = newLocale;
    };

    const setTheme = (newTheme: string) => {
        theme.value = newTheme;
    };

    return {
        villageProfile,
        settings,
        locale,
        theme,
        setVillageProfile,
        setSettings,
        getSetting,
        setLocale,
        setTheme,
    };
});
