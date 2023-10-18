<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v14/services/keyword_plan_idea_service.proto

namespace Google\Ads\GoogleAds\V14\Services;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * An ad group that is part of a campaign to be forecasted.
 *
 * Generated from protobuf message <code>google.ads.googleads.v14.services.ForecastAdGroup</code>
 */
class ForecastAdGroup extends \Google\Protobuf\Internal\Message
{
    /**
     * The max cpc to use for the ad group when generating forecasted traffic.
     * This value will override the max cpc value set in the bidding strategy.
     * Only specify this field for bidding strategies that max cpc values.
     *
     * Generated from protobuf field <code>optional int64 max_cpc_bid_micros = 1;</code>
     */
    protected $max_cpc_bid_micros = null;
    /**
     * Required. The list of biddable keywords to be used in the ad group when
     * doing the forecast. Requires at least one keyword.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v14.services.BiddableKeyword biddable_keywords = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $biddable_keywords;
    /**
     * The details of the keyword. You should specify both the keyword text and
     * match type.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v14.common.KeywordInfo negative_keywords = 3;</code>
     */
    private $negative_keywords;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int|string $max_cpc_bid_micros
     *           The max cpc to use for the ad group when generating forecasted traffic.
     *           This value will override the max cpc value set in the bidding strategy.
     *           Only specify this field for bidding strategies that max cpc values.
     *     @type array<\Google\Ads\GoogleAds\V14\Services\BiddableKeyword>|\Google\Protobuf\Internal\RepeatedField $biddable_keywords
     *           Required. The list of biddable keywords to be used in the ad group when
     *           doing the forecast. Requires at least one keyword.
     *     @type array<\Google\Ads\GoogleAds\V14\Common\KeywordInfo>|\Google\Protobuf\Internal\RepeatedField $negative_keywords
     *           The details of the keyword. You should specify both the keyword text and
     *           match type.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Ads\GoogleAds\V14\Services\KeywordPlanIdeaService::initOnce();
        parent::__construct($data);
    }

    /**
     * The max cpc to use for the ad group when generating forecasted traffic.
     * This value will override the max cpc value set in the bidding strategy.
     * Only specify this field for bidding strategies that max cpc values.
     *
     * Generated from protobuf field <code>optional int64 max_cpc_bid_micros = 1;</code>
     * @return int|string
     */
    public function getMaxCpcBidMicros()
    {
        return isset($this->max_cpc_bid_micros) ? $this->max_cpc_bid_micros : 0;
    }

    public function hasMaxCpcBidMicros()
    {
        return isset($this->max_cpc_bid_micros);
    }

    public function clearMaxCpcBidMicros()
    {
        unset($this->max_cpc_bid_micros);
    }

    /**
     * The max cpc to use for the ad group when generating forecasted traffic.
     * This value will override the max cpc value set in the bidding strategy.
     * Only specify this field for bidding strategies that max cpc values.
     *
     * Generated from protobuf field <code>optional int64 max_cpc_bid_micros = 1;</code>
     * @param int|string $var
     * @return $this
     */
    public function setMaxCpcBidMicros($var)
    {
        GPBUtil::checkInt64($var);
        $this->max_cpc_bid_micros = $var;

        return $this;
    }

    /**
     * Required. The list of biddable keywords to be used in the ad group when
     * doing the forecast. Requires at least one keyword.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v14.services.BiddableKeyword biddable_keywords = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getBiddableKeywords()
    {
        return $this->biddable_keywords;
    }

    /**
     * Required. The list of biddable keywords to be used in the ad group when
     * doing the forecast. Requires at least one keyword.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v14.services.BiddableKeyword biddable_keywords = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param array<\Google\Ads\GoogleAds\V14\Services\BiddableKeyword>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setBiddableKeywords($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Ads\GoogleAds\V14\Services\BiddableKeyword::class);
        $this->biddable_keywords = $arr;

        return $this;
    }

    /**
     * The details of the keyword. You should specify both the keyword text and
     * match type.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v14.common.KeywordInfo negative_keywords = 3;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getNegativeKeywords()
    {
        return $this->negative_keywords;
    }

    /**
     * The details of the keyword. You should specify both the keyword text and
     * match type.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v14.common.KeywordInfo negative_keywords = 3;</code>
     * @param array<\Google\Ads\GoogleAds\V14\Common\KeywordInfo>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setNegativeKeywords($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Ads\GoogleAds\V14\Common\KeywordInfo::class);
        $this->negative_keywords = $arr;

        return $this;
    }

}

