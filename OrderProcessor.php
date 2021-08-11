<?
class OrderProcessor {
 
    public function __construct(BillerInterface $biller)
    {
        $this->biller = $biller;
    }

    public function process(Order $order)
    {

        if ($this->getRecentOrderCount($order) > 0)
        {
            throw new Exception('Duplicate order likely.');
        }
        // 這是扣庫存 ?
        $this->biller->bill($order->account->id, $order->amount);

        $this->createOrder($order);
        
    }

    protected function getRecentOrderCount(Order $order)
    {
        $timestamp = Carbon::now()->subMinutes(5);

        return DB::table('orders')
            ->where('account', $order->account->id)
            ->where('created_at', '>=', $timestamps)
            ->count();
    }
    
    proteced function createOrder(Order $order)
    {
        DB::table('orders')->create([
            'account'    => $order->account->id,
            'amount'     => $order->amount;
        ]);
    }
}
