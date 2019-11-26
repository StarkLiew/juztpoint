import app from '$receipt/app';
import renderVueComponentToString from 'vue-server-renderer/basic';

renderVueComponentToString(app, (err, html) => {
    if (err) {
        // throw new Error(err);
        dispatch('<p>;(</p>');
    }

    dispatch(html);
}); 
