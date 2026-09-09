import { AIContent } from '@/types/user';
import 'quill/dist/quill.snow.css';
import { GeneratedContentEditor } from '../forms/content-editor-form';

export const EditContent = ({ t, content }: { t: (key: string) => string; content: AIContent }) => {
    return (
        <div>
            <GeneratedContentEditor
                t={t}
                content={content.content ?? ''}
                generationId={content.generation_id}
                url="dashboard.user.content-generator.update"
            />
        </div>
    );
};
