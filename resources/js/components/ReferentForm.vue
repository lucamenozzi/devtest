<template>
    <form @submit.prevent="submitForm">
        <Input
            type="text"
            id="searchReferent"
            placeholder="Search referent"
            class="mb-12"
            autocomplete="off"
            v-model="search"
            @input="fetchReferents($event.target.value)"
        />
        <div
            v-if="referents.length && search.length >= minimumNumberOfCharsToStartSearch"
            class="dropdown"
            style="
                position: absolute;
                width: 100%;
                background: darkslategrey;
                border: 1px solid #ddd;
                border-top: none;
                max-height: 200px;
                overflow-y: auto;
                z-index: 1000;
            "
        >
            <div
                v-for="referent in referents"
                :key="referent.id"
                @click="addReferentToPoint(referent)"
                @mouseover="$event.target.style.background = 'grey'"
                @mouseleave="$event.target.style.background = 'darkslategrey'"
            >
                {{ referent.name }} {{ referent.last_name }} ({{ referent.email }})
            </div>
        </div>
        <div v-if="loadingFetchReferents">Loading...</div>

        <Input
            type="text"
            id="name"
            placeholder="Name"
            v-model="form.name"
            required
            class="mb-2"
        />

        <Input
            type="text"
            id="last_name"
            placeholder="Last Name"
            v-model="form.last_name"
            class="mb-2"
            required
        />

        <Input
            type="email"
            id="email"
            placeholder="Email"
            v-model="form.email"
            class="mb-2"
            required
        />

        <Input
            type="tel"
            id="phone"
            placeholder="Phone"
            v-model="form.phone"
            class="mb-2"
            required
        />

        <Button type="submit" variant="outline" :disabled="loading">
            {{ loading ? 'Saving...' : 'Save' }}
        </Button>
    </form>
</template>

<script setup lang="ts">
import axios from 'axios';
import { reactive, ref } from 'vue';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';

const search = ref('');
const referents = ref([]);
const loadingFetchReferents = ref(false);
const minimumNumberOfCharsToStartSearch = 3

const fetchReferents = async (charsInserted: string) => {
    if (charsInserted.length < minimumNumberOfCharsToStartSearch) {
        referents.value = [];
        return;
    }

    loadingFetchReferents.value = true;

    try {
        const response = await axios.get('/referent/search', {
            params: { search: search.value },
        });
        referents.value = response.data;
    } catch (error) {
        console.error(error);
    } finally {
        loadingFetchReferents.value = false;
    }
};

const props = defineProps<{
    shipmentId: number | string;
    scope: string;
}>();

const emit = defineEmits<{
    'referent-added': [data: any];
    error: [error: any];
}>();

const form = reactive({
    name: '',
    last_name: '',
    email: '',
    phone: '',
    scope: props.scope,
});

const loading = ref(false);

const submitForm = async () => {
    loading.value = true;
    console.log(form);
    try {
        const response = await axios.post(
            `/shipments/${props.shipmentId}/addReferent`,
            form,
        );
        emit('referent-added', response.data);
        resetForm();
        //reload page
        window.location.reload();
    } catch (error) {
        console.error('Error saving referent:', error);
        emit('error', error);
    } finally {
        loading.value = false;
    }
};

const addReferentToPoint = async (referent: object) => {
    console.log(referent)
    loading.value = true;
    try {
        const response = await axios.post(
            `/shipments/${props.shipmentId}/addReferentToPoint`,
            {
                referentId: referent.id,
                scope:  props.scope
            },
        );
        emit('referent-added', response.data);
        resetForm();
        //reload page
        window.location.reload();
    } catch (error) {
        console.error('Error saving referent:', error);
        emit('error', error);
    } finally {
        loading.value = false;
    }
};

const resetForm = () => {
    form.name = '';
    form.last_name = '';
    form.email = '';
    form.phone = '';
};
</script>
