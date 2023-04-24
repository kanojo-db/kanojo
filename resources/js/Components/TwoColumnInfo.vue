<template>
    <v-container>
        <v-row>
            <v-col
                :order="2"
                :order-lg="1"
                :cols="12"
                :lg="9"
                :xl="10"
            >
                <slot name="left" />
            </v-col>

            <v-col
                :order="1"
                :order-lg="2"
                :cols="12"
                :lg="3"
                :xl="2"
            >
                <slot name="right" />
            </v-col>
        </v-row>
    </v-container>
</template>
