<template>
    <div class="relative w-full mb-12">
        <Input
            type="text"
            id="searchReferent"
            placeholder="Search referent"
            autocomplete="off"
            v-model="search"
            @input="fetchReferents($event.target.value)"
            @focus="dropdownVisible = true"
            @blur="closeDropdown"
            class="w-full"
        />

        <transition name="fade">
            <div
                v-if="dropdownVisible && search.length >= minimumNumberOfCharsToStartSearch"
                class="absolute w-full mt-1 bg-gray-800 border border-gray-700 rounded-xl shadow-xl max-h-60 overflow-y-auto overflow-x-hidden z-50 backdrop-blur-sm"
            >
                <div
                    v-if="loadingFetchReferents"
                    class="flex justify-center py-4"
                >
                    <div class="h-5 w-5 border-2 border-gray-400 border-t-transparent rounded-full animate-spin"></div>
                </div>

                <div
                    v-else-if="referents.length === 0"
                    class="px-4 py-3 text-gray-400 italic text-sm"
                >
                    Nessun risultato
                </div>

                <div
                    v-for="referent in referents"
                    :key="referent.id"
                    @click="addReferentToPoint(referent)"
                    class="px-4 py-3 cursor-pointer transition-all duration-150 hover:bg-gray-700 hover:scale-[1.01] active:scale-[0.99] w-full"
                >
                    <div class="flex justify-between items-center w-full overflow-hidden">
                        <div class="text-sm font-medium text-white truncate">
                            {{ referent.name }} {{ referent.last_name }}
                        </div>
                        <div class="text-xs text-gray-400 truncate max-w-[140px]">
                            {{ referent.email }}
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

 <script setup lang="ts">
 import axios from 'axios';
 import { ref } from 'vue';
 import { Input } from '@/components/ui/input';
 const dropdownVisible = ref(false);
 const search = ref('');
 const referents = ref([]);
 const loadingFetchReferents = ref(false);
 const minimumNumberOfCharsToStartSearch = 3;

 const props = defineProps<{
     shipmentId: number | string;
     scope: string;
 }>();

 const closeDropdown = () => {
     setTimeout(() => {
         dropdownVisible.value = false;
     }, 100);
 };

 const addReferentToPoint = async (referent: object) => {
     console.log(referent)
     loadingFetchReferents.value = true;
     try {
         const response = await axios.post(
             `/shipments/${props.shipmentId}/addReferentToPoint`,
             {
                 referentId: referent.id,
                 scope:  props.scope
             },
         );
         emit('referent-added', response.data);
         //reload page
         window.location.reload();
     } catch (error) {
         console.error('Error saving referent:', error);
         emit('error', error);
     } finally {
         loadingFetchReferents.value = false;
     }
 };

 const fetchReferents = async (charsInserted: string) => {
     if (charsInserted.length < minimumNumberOfCharsToStartSearch) {
         referents.value = [];
         return;
     }

     loadingFetchReferents.value = true;

     try {
         const response = await axios.get('/referent/search', {
             params: {
                 search: search.value ,
                 shipmentId: props.shipmentId,
                 scope: props.scope
             },
         });
         referents.value = response.data;
     } catch (error) {
         console.error(error);
     } finally {
         loadingFetchReferents.value = false;
     }
 };


 </script>
