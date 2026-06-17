import React, { useState, useMemo, useEffect } from 'react';

export default function SplitBillApp() {
  const [items, setItems] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [selectedItems, setSelectedItems] = useState([]);
  const [showQris, setShowQris] = useState(false);
  const [paymentMode, setPaymentMode] = useState('single'); // 'single' | 'split'
  const [paymentMethod, setPaymentMethod] = useState('qris'); // 'qris' | 'cash'
  const [paidOrderIds, setPaidOrderIds] = useState([]);
  const [sessionId] = useState(() => Math.random().toString(36).substring(2, 10));

  // Ambil tabel dari URL
  const tableNumber = new URLSearchParams(window.location.search).get('table') || '12';

  useEffect(() => {
    // Load cart from localStorage instead of demo API
    const savedCart = localStorage.getItem('warmindo_cart');
    if (savedCart) {
      try {
        const cartData = JSON.parse(savedCart);
        // Transform object format { 'id': { qty: 2, ... } } to array format if needed
        // Assuming menu.php saves as an array of objects, or object map
        const itemsArray = Array.isArray(cartData) ? cartData : Object.values(cartData);
        
        // Ensure all items have 'status: unpaid' initially
        const formattedItems = itemsArray.map(item => ({
          id: parseInt(item.id),
          name: item.name,
          price: parseFloat(item.price),
          qty: parseInt(item.qty),
          status: 'unpaid'
        })).filter(item => item.qty > 0);
        
        setItems(formattedItems);
      } catch (e) {
        console.error('Failed to parse cart', e);
        setItems([]);
      }
    }
    setIsLoading(false);
  }, []);

  const toggleSelect = (id) => {
    if (paymentMode !== 'split') return;
    setSelectedItems(prev => 
      prev.includes(id) ? prev.filter(item => item !== id) : [...prev, id]
    );
  };

  const totalBill = useMemo(() => {
    return items.reduce((acc, item) => acc + (item.price * item.qty), 0);
  }, [items]);

  const unpaidItems = useMemo(() => items.filter(item => item.status === 'unpaid'), [items]);
  const unpaidTotal = useMemo(() => unpaidItems.reduce((acc, item) => acc + (item.price * item.qty), 0), [unpaidItems]);

  const selectedTotal = useMemo(() => {
    if (paymentMode === 'single') return unpaidTotal;
    return items
      .filter(item => selectedItems.includes(item.id))
      .reduce((acc, item) => acc + (item.price * item.qty), 0);
  }, [items, selectedItems, paymentMode, unpaidTotal]);

  const checkoutItemsCount = useMemo(() => {
    if (paymentMode === 'single') return unpaidItems.length;
    return selectedItems.length;
  }, [selectedItems, paymentMode, unpaidItems]);

  const handlePay = () => {
    if (selectedTotal > 0) {
      if (paymentMethod === 'qris') {
        setShowQris(true);
      }
      
      const payload = {
        sessionId: sessionId,
        tableNumber: tableNumber,
        paymentMode: paymentMode,
        paymentMethod: paymentMethod,
        total: selectedTotal,
        items: items,
        selectedItems: selectedItems
      };

      fetch('/api/checkout', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        const delay = paymentMethod === 'qris' ? 1500 : 0;
        setTimeout(() => {
          if (paymentMode === 'single') {
            window.location.href = '/track/' + data.orderId;
          } else {
            setItems(prev => prev.map(item => {
              return selectedItems.includes(item.id) ? { ...item, status: 'paid' } : item;
            }));
            setSelectedItems([]);
            setShowQris(false);
            setPaidOrderIds(prev => [...prev, data.orderId]);
            if (paymentMethod === 'cash') {
              alert('Pesanan terkirim! Silakan menuju kasir untuk melakukan pembayaran tunai.');
            }
          }
        }, delay);
      })
      .catch(err => {
        console.error('Checkout failed', err);
        setShowQris(false);
        alert('Terjadi kesalahan saat memproses pembayaran.');
      });
    }
  };

  if (isLoading) {
    return <div className="p-10 text-center text-gray-500">Memuat data menu...</div>;
  }

  return (
    <div className="pb-36 bg-gray-50 min-h-screen font-sans">
      {/* Header handled by main layout */}
      <div className="pt-6 px-5">
        {paidOrderIds.length > 0 && (
          <div className="mb-6">
            <div className="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl flex items-center justify-between shadow-sm">
              <span className="text-sm font-bold flex items-center gap-2">
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path></svg>
                Sebagian/Semua Pesanan Dibayar
              </span>
              <a href={`/track/${paidOrderIds[0]}`} target="_blank" rel="noreferrer" className="text-xs font-bold text-green-800 bg-green-200 px-4 py-2 rounded-lg hover:bg-green-300 transition-colors shadow-sm whitespace-nowrap ml-2">
                Lacak Pesanan
              </a>
            </div>
          </div>
        )}

        <div className="bg-white p-2 rounded-xl border border-gray-200 flex gap-1 mb-6 shadow-sm">
          <button 
            onClick={() => { setPaymentMode('single'); setPaymentMethod('qris'); }}
            className={`flex-1 py-2 text-sm font-bold rounded-lg transition-colors ${paymentMode === 'single' ? 'bg-yellow-500 text-white' : 'text-gray-600 hover:bg-gray-100'}`}
          >
            Bayar Semua
          </button>
          <button 
            onClick={() => { setPaymentMode('split'); setPaymentMethod('qris'); }}
            className={`flex-1 py-2 text-sm font-bold rounded-lg transition-colors ${paymentMode === 'split' ? 'bg-yellow-500 text-white' : 'text-gray-600 hover:bg-gray-100'}`}
          >
            Split Bill
          </button>
        </div>

        {paymentMode === 'split' && (
          <div className="mb-4 bg-yellow-50 border border-yellow-200 p-3 rounded-lg text-yellow-800 flex items-center gap-2">
            <svg className="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span className="text-sm font-bold">Pilih menu yang ingin Anda bayar</span>
          </div>
        )}

        <div className="space-y-3">
          {items.map(item => {
            const isSelected = selectedItems.includes(item.id);
            const isPaid = item.status === 'paid';
            
            return (
              <div 
                key={item.id} 
                onClick={() => item.status === 'unpaid' && toggleSelect(item.id)}
                className={`p-4 rounded-xl flex items-center justify-between border ${
                  paymentMode === 'split' && item.status === 'unpaid' ? 'cursor-pointer hover:bg-yellow-50' : ''
                } ${
                  isPaid ? 'opacity-50 bg-gray-100 border-gray-200' : 
                  isSelected ? 'bg-yellow-50 border-yellow-400' : 
                  'bg-white border-gray-200 shadow-sm'
                }`}
              >
                <div className="flex items-center gap-3">
                  {paymentMode === 'split' && !isPaid && (
                    <div className="shrink-0">
                      <div className={`w-5 h-5 rounded flex items-center justify-center border ${
                        isSelected ? 'bg-yellow-500 border-yellow-500 text-white' : 'bg-white border-gray-300'
                      }`}>
                        {isSelected && (
                          <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M5 13l4 4L19 7"></path></svg>
                        )}
                      </div>
                    </div>
                  )}
                  
                  <div>
                    <h3 className={`font-bold text-sm mb-0.5 ${isPaid ? 'line-through text-gray-500' : 'text-gray-800'}`}>
                      {item.name}
                    </h3>
                    <p className="text-xs text-gray-500 font-medium">
                      <span className="bg-gray-200 px-1.5 py-0.5 rounded text-xs mr-2">{item.qty}x</span> 
                      Rp {item.price.toLocaleString('id-ID')}
                    </p>
                  </div>
                </div>
                <div className="text-right shrink-0">
                   {isPaid ? (
                     <span className="text-[10px] font-bold uppercase text-white bg-gray-400 px-2 py-1 rounded">Lunas</span>
                   ) : (
                     <p className={`font-bold text-sm ${isSelected ? 'text-yellow-600' : 'text-gray-800'}`}>
                       Rp {(item.price * item.qty).toLocaleString('id-ID')}
                     </p>
                   )}
                </div>
              </div>
            );
          })}
        </div>

        {showQris && (
          <div className="fixed inset-0 bg-black/60 z-[60] flex items-center justify-center p-4">
            <div className="bg-white rounded-2xl p-6 w-full max-w-sm text-center shadow-2xl">
              <h3 className="font-bold text-xl text-gray-900 mb-1">Scan QRIS</h3>
              <p className="text-sm text-gray-600 mb-6">Total: <span className="font-bold text-yellow-600 text-lg">Rp {selectedTotal.toLocaleString('id-ID')}</span></p>
              
              <div className="mx-auto w-48 h-48 border border-gray-200 rounded-xl mb-6 bg-white p-2">
                  <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="QRIS" className="w-full h-full object-contain"/>
              </div>

              <div className="flex items-center justify-center gap-2 text-sm font-bold text-yellow-600 bg-yellow-50 py-2 rounded-lg">
                <svg className="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle><path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Menunggu Pembayaran...
              </div>
            </div>
          </div>
        )}

      </div>

      <div className="fixed bottom-0 left-0 right-0 bg-white px-5 py-5 z-50 rounded-t-3xl shadow-[0_-10px_40px_rgba(0,0,0,0.06)]">
        <div className="max-w-md mx-auto">
          <div className="flex gap-3 mb-5">
            <button 
              onClick={() => setPaymentMethod('qris')}
              className={`flex-1 py-2.5 rounded-full text-[13px] font-bold border-2 transition-colors flex justify-center items-center gap-2 ${paymentMethod === 'qris' ? 'bg-[#F0F5FF] border-[#2563EB] text-[#2563EB]' : 'bg-white border-[#E2E8F0] text-[#64748B] hover:bg-gray-50'}`}>
              <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
              QRIS
            </button>
            {paymentMode !== 'split' && (
              <button 
                onClick={() => setPaymentMethod('cash')}
                className={`flex-1 py-2.5 rounded-full text-[13px] font-bold border-2 transition-colors flex justify-center items-center gap-2 ${paymentMethod === 'cash' ? 'bg-[#F0F5FF] border-[#2563EB] text-[#2563EB]' : 'bg-white border-[#E2E8F0] text-[#64748B] hover:bg-gray-50'}`}>
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Tunai / Kasir
              </button>
            )}
          </div>
          
          <div className="flex items-center justify-between mb-5">
            <div>
              <p className="text-[#64748B] text-[11px] font-bold uppercase tracking-wider mb-1">Total Tagihan</p>
              <p className="text-[#0F172A] font-black text-[22px] tracking-tight leading-none">Rp {selectedTotal.toLocaleString('id-ID')}</p>
            </div>
            <span className="text-[#F59E0B] text-xs font-extrabold bg-[#FEF3C7] px-3 py-1.5 rounded-lg">{checkoutItemsCount} Item</span>
          </div>

          <button 
            onClick={handlePay}
            disabled={selectedTotal === 0 || showQris}
            className={`w-full py-4 rounded-[20px] font-bold text-[15px] transition-colors flex items-center justify-center gap-2 ${
              selectedTotal > 0 && !showQris
                ? 'bg-[#111827] text-white hover:bg-[#1F2937]' 
                : 'bg-gray-200 text-gray-400 cursor-not-allowed'
            }`}
          >
            {showQris ? 'Memproses...' : 'Bayar Sekarang'}
            {!showQris && (
              <svg className="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            )}
          </button>
        </div>
      </div>
    </div>
  );
}
