/**
 * Animation Utilities
 * Common animation configurations and helpers
 */

import {Animated, Easing} from 'react-native';

/**
 * Fade In Animation
 */
export const fadeIn = (animatedValue, duration = 300, delay = 0) => {
  return Animated.timing(animatedValue, {
    toValue: 1,
    duration,
    delay,
    easing: Easing.ease,
    useNativeDriver: true,
  });
};

/**
 * Fade Out Animation
 */
export const fadeOut = (animatedValue, duration = 300, delay = 0) => {
  return Animated.timing(animatedValue, {
    toValue: 0,
    duration,
    delay,
    easing: Easing.ease,
    useNativeDriver: true,
  });
};

/**
 * Slide In From Bottom
 */
export const slideInFromBottom = (animatedValue, duration = 400, delay = 0) => {
  return Animated.timing(animatedValue, {
    toValue: 0,
    duration,
    delay,
    easing: Easing.out(Easing.cubic),
    useNativeDriver: true,
  });
};

/**
 * Slide Out To Bottom
 */
export const slideOutToBottom = (animatedValue, toValue = 100, duration = 300) => {
  return Animated.timing(animatedValue, {
    toValue,
    duration,
    easing: Easing.in(Easing.cubic),
    useNativeDriver: true,
  });
};

/**
 * Scale Animation
 */
export const scale = (animatedValue, toValue = 1, duration = 200) => {
  return Animated.spring(animatedValue, {
    toValue,
    friction: 5,
    tension: 40,
    useNativeDriver: true,
  });
};

/**
 * Pulse Animation (Loop)
 */
export const pulse = animatedValue => {
  return Animated.loop(
    Animated.sequence([
      Animated.timing(animatedValue, {
        toValue: 1.1,
        duration: 800,
        easing: Easing.ease,
        useNativeDriver: true,
      }),
      Animated.timing(animatedValue, {
        toValue: 1,
        duration: 800,
        easing: Easing.ease,
        useNativeDriver: true,
      }),
    ]),
  );
};

/**
 * Shake Animation
 */
export const shake = animatedValue => {
  return Animated.sequence([
    Animated.timing(animatedValue, {
      toValue: 10,
      duration: 100,
      useNativeDriver: true,
    }),
    Animated.timing(animatedValue, {
      toValue: -10,
      duration: 100,
      useNativeDriver: true,
    }),
    Animated.timing(animatedValue, {
      toValue: 10,
      duration: 100,
      useNativeDriver: true,
    }),
    Animated.timing(animatedValue, {
      toValue: 0,
      duration: 100,
      useNativeDriver: true,
    }),
  ]);
};

/**
 * Bounce Animation
 */
export const bounce = (animatedValue, toValue = 1) => {
  return Animated.spring(animatedValue, {
    toValue,
    friction: 2,
    tension: 40,
    useNativeDriver: true,
  });
};

/**
 * Rotate Animation (Loop)
 */
export const rotate = animatedValue => {
  return Animated.loop(
    Animated.timing(animatedValue, {
      toValue: 1,
      duration: 1000,
      easing: Easing.linear,
      useNativeDriver: true,
    }),
  );
};

/**
 * Stagger Animation
 * Animates multiple items with delay
 */
export const stagger = (animations, delay = 100) => {
  return Animated.stagger(delay, animations);
};

/**
 * Parallel Animation
 * Runs multiple animations at once
 */
export const parallel = animations => {
  return Animated.parallel(animations);
};

/**
 * Sequence Animation
 * Runs animations one after another
 */
export const sequence = animations => {
  return Animated.sequence(animations);
};

/**
 * Create Animated Value Hook Helper
 */
export const useAnimatedValue = (initialValue = 0) => {
  return new Animated.Value(initialValue);
};

/**
 * Interpolation Helper
 */
export const interpolate = (animatedValue, inputRange, outputRange, extrapolate = 'clamp') => {
  return animatedValue.interpolate({
    inputRange,
    outputRange,
    extrapolate,
  });
};

/**
 * Card Enter Animation
 * Combines fade and slide for card entrance
 */
export const cardEnterAnimation = (fadeAnim, slideAnim) => {
  return parallel([
    fadeIn(fadeAnim, 400),
    slideInFromBottom(slideAnim, 400),
  ]);
};

/**
 * List Item Stagger Animation
 * Animates list items with stagger effect
 */
export const listItemStagger = (items, delay = 80) => {
  const animations = items.map(item => fadeIn(item, 300));
  return stagger(animations, delay);
};

/**
 * Button Press Animation
 * Scale down and back for button press feedback
 */
export const buttonPressAnimation = animatedValue => {
  return sequence([
    scale(animatedValue, 0.95, 100),
    scale(animatedValue, 1, 100),
  ]);
};

/**
 * Slide In From Right
 */
export const slideInFromRight = (animatedValue, duration = 300) => {
  return Animated.timing(animatedValue, {
    toValue: 0,
    duration,
    easing: Easing.out(Easing.cubic),
    useNativeDriver: true,
  });
};

/**
 * Slide In From Left
 */
export const slideInFromLeft = (animatedValue, duration = 300) => {
  return Animated.timing(animatedValue, {
    toValue: 0,
    duration,
    easing: Easing.out(Easing.cubic),
    useNativeDriver: true,
  });
};

/**
 * Progress Bar Animation
 */
export const progressAnimation = (animatedValue, toValue, duration = 1000) => {
  return Animated.timing(animatedValue, {
    toValue,
    duration,
    easing: Easing.ease,
    useNativeDriver: false, // Width animations don't support native driver
  });
};

/**
 * Spring Animation with Config
 */
export const springAnimation = (animatedValue, toValue, config = {}) => {
  return Animated.spring(animatedValue, {
    toValue,
    friction: 7,
    tension: 40,
    ...config,
    useNativeDriver: true,
  });
};
