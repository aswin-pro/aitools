export const assetUrl = (path?: string | null): string => {
    if (!path) return '';

    return new URL(path.replace(/^\/+/, ''), window.location.origin).toString();
};
