<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrentPriceProfileCollection;
use App\Http\Resources\DiscountCollection;
use App\Http\Resources\FinalisationCollection;
use App\Http\Resources\GiftCertificate as ResourcesGiftCertificate;
use App\Http\Resources\GradeCollection;
use App\Http\Resources\ItemTypeCollection;
use App\Http\Resources\PosTerminal as ResourcesPosTerminal;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ReceiptCollection;
use App\Http\Resources\SubAccountCollection;
use App\Http\Resources\TaxCollection;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\VehicleTypeCollection;
use App\Models\Barcode;
use App\Models\Discount;
use App\Models\Finalisation;
use App\Models\GiftCertificate;
use App\Models\Grade;
use App\Models\ItemType;
use App\Models\PosTerminal;
use App\Models\PriceProfile;
use App\Models\Product;
use App\Models\SubAccount;
use App\Models\Tax;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VehicleType;
use App\Services\GetCashierActiveShiftPeriodService;
use App\Services\GetGradesService;
use App\Services\GetReceiptLayoutService;
use App\Services\RefundCheckerService;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LookupController extends Controller
{
    use Response;

    /**
     * @OA\Post(
     * path="/api/check-test-del-stat",
     * summary="Check if can test del",
     * description="Previous Route: /enablerAPI/lookUpCtrl/checkTestDelStat",
     * operationId="authLogin",
     * tags={"Look Up"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass cashier credentials",
     *    @OA\JsonContent(
     *       required={"number","password"},
     *       @OA\Property(property="number", type="integer", format="number"),
     *       @OA\Property(property="password", type="string", format="password"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Allowed"),
     *       @OA\Property(property="data", type="number", example="int|string|object|array")
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="Not Allowed"),
     *       @OA\Property(property="data", type="number", example="int|string|object|array")
     *    )
     *  ),
     * )
     */
    public function checkTestDelStat(Request $request){
        $user = User::login($request->number, $request->password);

        if($user->test_del == 0){
            return $this->response(
                'Not Allowed',
                0,
                0
            );
        }

        return $this->response(
            'Allowed',
            1,
            1
        );
    }

    /**
     * @OA\Post(
     * path="/api/check-fuel-sale",
     * summary="Check if can fuel sale",
     * description="Previous Route: /enablerAPI/lookUpCtrl/checkFuelSale",
     * tags={"Look Up"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass cashier credentials",
     *    @OA\JsonContent(
     *       required={"number","password"},
     *       @OA\Property(property="number", type="integer", format="number"),
     *       @OA\Property(property="password", type="string", format="password"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Allowed"),
     *       @OA\Property(property="data", type="number", example="1")
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="Not Allowed"),
     *       @OA\Property(property="data", type="number", example="0")
     *    )
     *  ),
     * )
     */
    public function checkFuelSale(Request $request){
        $user = User::login($request->number, $request->password);

        if($user->bof == 0){
            return $this->response(
                'Not Allowed',
                0,
                0
            );
        }

        return $this->response(
            'Allowed',
            1,
            1
        );
    }

    /**
     * @OA\Post(
     * path="/api/cashier-active-shift-period",
     * summary="Get cashier active shift period",
     * description="Previous Route: /enablerAPI/lookUpCtrl/getCshrActShiftPeriod",
     * tags={"Look Up"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass cashier ID",
     *    @OA\JsonContent(
     *       required={"cashierID"},
     *       @OA\Property(property="cashierID", type="integer", format="number"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Success"),
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="Failed to retrieve period ID"),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     */
    public function getCashierActiveShiftPeriod(Request $request){

        $service = new GetCashierActiveShiftPeriodService($request->cashierID);
        $result = $service->execute();

        if(!$result['success']){
            return $this->response(
                $result['message'],
                0
            );
        }

        return $this->response(
            $result['message'],
            1,
            $result['data']
        );
    }

    /**
     * @OA\Get(
     * path="/api/taxes",
     * summary="Get tax list",
     * description="Previous Route: /enablerAPI/lookUpCtrl/getTaxList",
     * tags={"Look Up"},
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Success"),
     *       @OA\Property(property="data", type="string", example="int|string|object|array"),
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="Failed to retrieve tax list"),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     */
    public function taxes(Request $request){

        $items = Tax::all();

        if( count($items) == 0){
            return $this->response(
                'Failed to retrieve tax list',
                0
            );
        }

        return $this->response(
            'Success',
            1,
            new TaxCollection($items)
        );
    }

    /**
     * @OA\Get(
     * path="/api/finalisations",
     * summary="Get finalisations list",
     * description="Previous Route: /enablerAPI/lookUpCtrl/getAllFinalisation",
     * tags={"Look Up"},
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Success"),
     *       @OA\Property(property="data", type="string", example="int|string|object|array"),
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="Failed to retrieve mop list"),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     */
    public function finalisations(Request $request){

        $items = Finalisation::all();

        if( count($items) == 0){
            return $this->response(
                'Failed to retrieve mop list',
                0
            );
        }

        return $this->response(
            'Success',
            1,
            new FinalisationCollection($items)
        );
    }

    /**
     * @OA\Get(
     * path="/api/grades",
     * summary="Get grades list",
     * description="Previous Route: /enablerAPI/lookUpCtrl/getGrades",
     * tags={"Look Up"},
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Success"),
     *       @OA\Property(property="data", type="string", example="int|string|object|array"),
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="Failed to retrieve grade list"),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     */
    public function grades(Request $request){

        $service = new GetGradesService;
        $result = $service->execute();

        if(!$result['success']){
            return $this->response(
                $result['message'],
                0
            );
        }

        return $this->response(
            $result['message'],
            1,
            new GradeCollection($result['data'])
        );
    }

    /**
     * @OA\Post(
     * path="/api/pos-terminal",
     * summary="Get POS Terminal",
     * description="Previous Route: /enablerAPI/lookUpCtrl/getPOSTerminal",
     * tags={"Look Up"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass POS ID",
     *    @OA\JsonContent(
     *       required={"posID"},
     *       @OA\Property(property="posID", type="integer", format="number"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Success"),
     *       @OA\Property(property="data", type="number", example="int|string|object|array")
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="Failed to retrieve period ID"),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     * @param Request $request
     * @return Response
     */
    public function receiptLayout(Request $request){
        $terminal = PosTerminal::find($request->posID);

        if(!$terminal){
            return $this->response(
                'Failed to retrieve period ID',
                0
            );
        }

        return $this->response(
            'Success',
            1,
            new ResourcesPosTerminal($terminal)
        );
    }

    /**
     * @OA\Get(
     * path="/api/item-types",
     * summary="Get item types",
     * description="Previous Route: /enablerAPI/lookUpCtrl/getItemTypes",
     * tags={"Look Up"},
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Success"),
     *       @OA\Property(property="data", type="string", example="int|string|object|array"),
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="Failed to retrieve item types"),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     */
    public function itemTypes(Request $request){
        $items = ItemType::all();

        if( count($items) == 0 ){
            return $this->response(
                'Failed to retrieve item types',
                0,
            );
        }

        return $this->response(
            'Success',
            1,
            new ItemTypeCollection($items)
        );
    }

    /**
     * @OA\Get(
     * path="/api/non-fuel-products",
     * summary="Get All Non-Fuel Products",
     * description="Previous Route: /enablerAPI/lookUpCtrl/getAllProducts",
     * tags={"Look Up"},
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Success"),
     *       @OA\Property(property="data", type="string", example="int|string|object|array"),
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="Failed to retrieve products"),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     */
    public function nonFuelProducts(Request $request){
        $items = Product::where('Department_ID', 2)->get();

        if( count($items) == 0 ){
            return $this->response(
                'Failed to retrieve products',
                0,
            );
        }

        return $this->response(
            'Success',
            1,
            new ProductCollection($items)
        );
    }

    /**
     * @OA\Get(
     * path="/api/products",
     * summary="Get All Products by Desciption|Barcode",
     * description="Previous Route: /enablerAPI/lookUpCtrl/getProductByDesc & /enablerAPI/lookUpCtrl/getProductByDesc",
     * tags={"Look Up"},
     * @OA\Parameter(
     *      name="search_type",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="array",
     *           @OA\Items(
     *              type="string",
     *              enum={"description", "barcode"},
     *              default="description"
     *          ),
     *      ),
     * ),
     * @OA\Parameter(
     *      name="search_value",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string",
     *      ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Success"),
     *       @OA\Property(property="data", type="string", example="int|string|object|array"),
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="No products found"),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     */
    public function products(Request $request){
        // return $request->all();
        $items = Product::select('*')
            ->join('Barcode', 'Barcode.Product_ID', '=', 'Products.Product_ID')
            ->when($request->search_type == 'description', function($query) use($request){
                $query->where('Product_Desc', 'LIKE', "%{$request->search_value}%");
            })
            ->when($request->search_type == 'barcode', function($query) use($request){
                $query->where('Barcode', $request->search_value);
            })
            ->get();

        if( count($items) == 0 ){
            return $this->response(
                'No products found',
                0,
            );
        }

        return $this->response(
            'Success',
            1,
            new ProductCollection($items)
        );
    }

    /**
     * @OA\Get(
     * path="/api/vehicle-types",
     * summary="Get vehicle types",
     * description="Previous Route: /enablerAPI/lookUpCtrl/getAllVehicleType",
     * tags={"Look Up"},
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Success"),
     *       @OA\Property(property="data", type="string", example="int|string|object|array"),
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="Failed to retrieve vehicle type / nothing to display."),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     */
    public function vehicleTypes(Request $request){
        $items = VehicleType::all();

        if( count($items) == 0 ){
            return $this->response(
                'Failed to retrieve vehicle type / nothing to display.',
                0,
            );
        }

        return $this->response(
            'Success',
            1,
            new VehicleTypeCollection($items)
        );
    }

    /**
     * @OA\Post(
     * path="/api/sub-account-details",
     * summary="Get Sub Account Details",
     * description="Previous Route: /enablerAPI/lookUpCtrl/getSubAccDetails",
     * tags={"Look Up"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Sub Account Number",
     *    @OA\JsonContent(
     *       required={"subAccNum"},
     *       @OA\Property(property="subAccNum", type="number", format="number"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Success"),
     *       @OA\Property(property="data", type="number", example="int|string|object|array")
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="No subaccount found"),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     * @param Request $request
     * @return Response
     */
    public function subAccountDetails(Request $request){
        $items = SubAccount::where('SubAcc_Number', $request->subAccNum)->get();

        if(count($items) == 0){
            return $this->response(
                'No subaccount found',
                0
            );
        }

        return $this->response(
            'Success',
            1,
            new SubAccountCollection($items)
        );
    }

    /**
     * @OA\Post(
     * path="/api/check-price-profile-exist",
     * summary="Check if price profile exist",
     * description="Previous Route: /enablerAPI/lookUpCtrl/isExistPriceProfile",
     * tags={"Look Up"},
     * @OA\RequestBody(
     *    required=true,
     *    description="",
     *    @OA\JsonContent(
     *       required={"gradeID", "date"},
     *       @OA\Property(property="gradeID", type="number", format="number"),
     *       @OA\Property(property="date", type="date", format="date", example="yyyy-mm-dd"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Success"),
     *       @OA\Property(property="data", type="number", example="int|string|object|array")
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="Price profile for grade already exists"),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     * @param Request $request
     * @return Response
     */
    public function checkPriceProfileExist(Request $request){

        if(!$request->gradeID){
            return $this->response(
                'Missing Parameter',
                3
            );
        }

        if(!$request->date){
            return $this->response(
                'Missing Parameter',
                3
            );
        }

        $items = PriceProfile::checkIfPriceProfileExist(
            $request->gradeID,
            $request->date
        )->get();

        if( count($items) > 0 ){
            return $this->response(
                'Price profile for grade already exists',
                0
            );
        }

        return $this->response(
            'Success'
        );
    }

    /**
     * @OA\Post(
     * path="/api/check-gift-certificate",
     * summary="Check if gift certificate exist",
     * description="Previous Route: /enablerAPI/lookUpCtrl/getGC",
     * tags={"Look Up"},
     * @OA\RequestBody(
     *    required=true,
     *    description="",
     *    @OA\JsonContent(
     *       required={"giftCertNum"},
     *       @OA\Property(property="giftCertNum", type="string", format="string"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Success"),
     *       @OA\Property(property="data", type="number", example="int|string|object|array")
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="No gift certificate found|Gift certificate already in use"),
     *       @OA\Property(property="data", type="null", example="null")
     *    ),
     *  ),
     * )
     * @param Request $request
     * @return Response
     */
    public function checkGiftCertificate(Request $request){
        $gc = GiftCertificate::where('giftcert_number', $request->giftCertNum)->first();

        if(!$gc){
            return $this->response(
                'No gift certificate found',
                0
            );
        }

        if($gc->status != 0){
            return $this->response(
                'Gift certificate already in use',
                0
            );
        }

        return $this->response(
            'Success',
            1,
            new ResourcesGiftCertificate($gc)
        );
    }

    /**
     * @OA\Post(
     * path="/api/refund-checker",
     * summary="Refund Checker by Transaction number and resetter referrence",
     * description="Previous Route: /enablerAPI/lookUpCtrl/refundChecker",
     * tags={"Look Up"},
     * @OA\RequestBody(
     *    required=true,
     *    description="",
     *    @OA\JsonContent(
     *       required={"transaction_number","transaction_resetter_reference"},
     *       @OA\Property(property="transaction_number", type="number", format="number"),
     *       @OA\Property(property="transaction_resetter_reference", type="number", format="number"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Transaction Number is already been refund."),
     *       @OA\Property(property="data", type="number", example="int|string|object|array")
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="Not found"),
     *       @OA\Property(property="data", type="null", example="null")
     *    ),
     *  ),
     * )
     * @param Request $request
     * @return Response
     */
    public function refundChecker(Request $request){

        $items = Transaction::select('transaction_number_reference')
            ->refundChecker($request->transaction_number, $request->transaction_resetter_reference)
            ->get();

        if( count($items) == 0){
            return $this->response(
                'Not Found',
                0
            );
        }

        return $this->response(
            'Transaction Number is already been refund.',
            1,
            new TransactionCollection($items)
        );

    }

    /**
     * @OA\Get(
     * path="/api/discounts",
     * summary="Get Discounts",
     * description="Previous Route: /enablerAPI/lookUpCtrl/getDiscountList",
     * tags={"Look Up"},
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Success"),
     *       @OA\Property(property="data", type="string", example="int|string|object|array"),
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="Discount list not found"),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     */
    public function discounts(Request $request){
        $items = Discount::with('presets')->visible()->get();

        if( count($items) == 0){
            return $this->response(
                'Discount list not found',
                0
            );
        }

        return $this->response(
            'Success',
            1,
            new DiscountCollection($items)
        );
    }

    /**
     * @OA\Get(
     * path="/api/current-price-profile",
     * summary="Get current price profile",
     * description="Previous Route: /enablerAPI/lookUpCtrl/getCurrentPriceProfile",
     * tags={"Look Up"},
     * @OA\Response(
     *    response=200,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="1"),
     *       @OA\Property(property="statusDescription", type="string", example="Success"),
     *       @OA\Property(property="data", type="string", example="int|string|object|array"),
     *    )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="",
     *    @OA\JsonContent(
     *       @OA\Property(property="statusCode", type="number", example="0"),
     *       @OA\Property(property="statusDescription", type="string", example="No price profile found"),
     *       @OA\Property(property="data", type="null", example="null")
     *    )
     *  ),
     * )
     */
    public function currentPriceProfile(Request $request){
        $items = Grade::select('*')
            ->leftJoin('Price_Levels', 'Price_Levels.Price_Profile_ID', '=', 'Grades.Price_Profile_ID')
            ->leftJoin('Price_Level_Types', 'Price_Level_Types.Price_Level', '=', 'Price_Levels.Price_Level')
            ->get();

        if( count($items) == 0){
            return $this->response('No price profile found', 0);
        }

        return $this->response('Success', 1, new CurrentPriceProfileCollection($items));
    }
}
