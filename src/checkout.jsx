import React from 'react';
import { createRoot } from 'react-dom/client';
import SplitBillApp from './components/SplitBillApp';

const container = document.getElementById('react-checkout-root');
if (container) {
    const root = createRoot(container);
    root.render(<SplitBillApp />);
}
