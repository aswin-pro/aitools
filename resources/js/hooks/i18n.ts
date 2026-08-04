import i18n from "i18next";
import { initReactI18next } from "react-i18next";
import resourcesToBackend from "i18next-resources-to-backend";

i18n
  .use(initReactI18next)
  .use(
    resourcesToBackend((lng: string) =>
      import(`../../../resources/lang/${lng}.json`)
    )
  )
  .init({
    fallbackLng: "en",
    lng: "en",
    interpolation: { escapeValue: false },
  });

export default i18n;
