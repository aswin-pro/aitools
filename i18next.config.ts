import { defineConfig } from "i18next-cli";

export default defineConfig({
    locales: ["en"],
    extract: {
        input: ["resources/js/**/*.{ts,tsx}"],
        output: "locales/{{language}}/{{namespace}}.json",
        defaultValue: (key) => key,
    },
});