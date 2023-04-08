<script setup>
import SidemenuBibleGeneral from '@/Components/SidemenuBibleGeneral.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});
</script>

<template>
    <div class="col bg-grey-3">
        <div class="row q-py-lg q-px-md">
            <h1 class="text-h4 q-mt-none q-mb-none ellipsis-2-lines">
                General Bible
            </h1>
        </div>
    </div>
    <div class="q-ma-md">
        <div class="row q-col-gutter-lg full-width">
            <div class="col-2 q-pl-none">
                <SidemenuBibleGeneral />
            </div>
            <div class="col col-10">
                <h2 class="text-h5 text-weight-bold q-mt-none q-mb-md">
                    General Tips
                </h2>
                <p class="text-body1">
                    Our data is a mix of user-contributed and scraped data. We
                    scrape multiple online stores every day for new releases. We
                    encourage you to provide additional information and add
                    missing titles to the database, or edit any incorrect data
                    you may come across. Click "Edit" on any movie, model,
                    series or studio to access the editing interface.
                </p>
                <p class="text-body1">
                    Please refrain from engaging in editing wars with other
                    contributors.
                </p>

                <h3 class="text-h6 text-weight-bold q-mt-none q-mb-md">
                    Reporting Issues
                </h3>
                <p class="text-body1">
                    Content issue reports are mostly handled by a team of
                    <strong>volunteer</strong> moderators. They can help you
                    perform many tasks such as deleting entries and images,
                    refreshing URLs or forcing a primary image. Use the
                    <strong>Report</strong> button to ask a moderator to
                    intervene and lock fields during an editing war or make a
                    final decision about reccuring issues.
                </p>
                <p class="text-body1">
                    Proving a source for your report is not required, but it can
                    help moderators to reach a decision faster.
                </p>
                <p class="text-body1">Reports should be written in English.</p>
                <p class="text-body1">
                    Do not erase data manually before submitting a report, as
                    moderators need to check the validity of the original data
                    to reach a decision.
                </p>

                <h2 class="text-h5 text-weight-bold q-mt-none q-mb-md">
                    Incorrect Entries
                </h2>
                <p class="text-body1">
                    We do not recycle entries. Please avoid repurposing existing
                    entries for new content.
                </p>
                <p class="text-body1">
                    Entries with incorrect data should be fixed, not deleted.
                </p>
                <p class="text-body1">
                    In case of duplicates, use the report function to ask a
                    moderator to merge the entries or remove the duplicate
                    entry. Do note that some movies exist in multiple versions
                    (For example a DVD and a Blu-ray version). Such entries can
                    coexist, since they feature a different product code.
                </p>
            </div>
        </div>
    </div>
</template>
