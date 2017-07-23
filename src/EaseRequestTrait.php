<?php

namespace EaseRequest;


trait EaseRequestTrait
{
    /**
     * Returns the rules to be used by FromRequest for validation
     *
     * @return array
     */
    public function rules()
    {
        $this->validateEaseRequest();

        return $this->modifyAndGetRules();
    }

    /**
     * Checks if preRules function has been defined
     *
     * @throws \Exception
     */
    public function validateEaseRequest()
    {
        if(! method_exists($this, $methodName = $this->getEaseRequestPreRulesFunctionName()))
        {
            throw new \Exception("Function $methodName must be defined.");
        }
    }

    /**
     * Returns the name of the function that must have been defined
     *
     * @return string
     */
    public function getEaseRequestPreRulesFunctionName()
    {
        return 'preRules';
    }

    /**
     * Updates the rules and return updated rules
     *
     * @return array
     */
    public function modifyAndGetRules()
    {
        $preRules = $this->getEaseRequestPreRules();

        $postRules = [];

        foreach ($preRules as $key => $rule)
        {
            $ruleToBeUpdated = $rule;

            $keysToBeReplaced = $this->getEaseRequestRuleKeysToBeUpdated($ruleToBeUpdated);

            $updatedRule = $this->updateEaseRequestRule($keysToBeReplaced, $ruleToBeUpdated);

            $postRules[$key] = $updatedRule;
        }

        return $postRules;
    }

    public function getEaseRequestPreRules()
    {
        $preRules = $this->preRules();

        if(is_null($preRules))
        {
            return [];
        }

        return $preRules;
    }

    /**
     * Returns the regex pattern to be used from replace the keys
     *
     * @return string
     */
    public function getEaseRequestPattern()
    {
        return '/\{\w+\}/';
    }

    /**
     * Gets keys that have to be replace from rule
     *
     * @param $rule string
     * @return array
     */
    public function getEaseRequestRuleKeysToBeUpdated($rule)
    {
        $pattern = $this->getEaseRequestPattern();

        preg_match_all($pattern, $rule, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

        return $matches;

    }

    /**
     * Updates the rule
     *
     * @param $keysToBeReplaced array
     * @param $rule string
     * @return string
     */
    public function updateEaseRequestRule($keysToBeReplaced, $rule)
    {
        foreach ($keysToBeReplaced as $match)
        {
            $keyToBeUpdated = $match[0][0];

            $rule = str_replace($keyToBeUpdated, $this->replaceEaseRequestKey($keyToBeUpdated), $rule);
        }

        return $rule;
    }

    /**
     * Updates the key with function's value or Request value
     *
     * @param $ruleKey
     * @return mixed
     */
    public function replaceEaseRequestKey($ruleKey)
    {
        $ruleKey = $this->cleanKey($ruleKey);

        if(method_exists($this, $ruleKey))
        {
            return $this->$ruleKey();
        }

        return $this->getValueFromRequest($ruleKey);
    }

    /**
     * Gets the value from Request
     *
     * @param $key
     * @return string
     */
    public function getValueFromRequest($key)
    {
        return \Request::get($key);
    }

    public function cleanKey($ruleKey)
    {
        return preg_replace('/[{}]/', '', $ruleKey);
    }
}