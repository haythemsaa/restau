/**
 * Reusable Button Component
 */

import React from 'react';
import {TouchableOpacity, Text, StyleSheet, ActivityIndicator} from 'react-native';
import LinearGradient from 'react-native-linear-gradient';
import Icon from 'react-native-vector-icons/Ionicons';
import {colors, typography, spacing, borderRadius, shadows} from '../utils/theme';

const Button = ({
  title,
  onPress,
  variant = 'primary', // primary, secondary, outline, ghost
  size = 'medium', // small, medium, large
  icon,
  iconPosition = 'left', // left, right
  loading = false,
  disabled = false,
  fullWidth = false,
  style,
}) => {
  const getGradientColors = () => {
    switch (variant) {
      case 'primary':
        return colors.gradient.primary;
      case 'secondary':
        return colors.gradient.secondary;
      case 'success':
        return colors.gradient.success;
      case 'warning':
        return colors.gradient.warning;
      case 'danger':
        return colors.gradient.danger;
      default:
        return colors.gradient.primary;
    }
  };

  const getSizeStyles = () => {
    switch (size) {
      case 'small':
        return {padding: spacing.sm, fontSize: typography.fontSize.sm};
      case 'large':
        return {padding: spacing.lg, fontSize: typography.fontSize.lg};
      default:
        return {padding: spacing.base, fontSize: typography.fontSize.base};
    }
  };

  const buttonStyles = [
    styles.button,
    fullWidth && styles.fullWidth,
    disabled && styles.disabled,
    style,
  ];

  const sizeStyles = getSizeStyles();

  if (variant === 'outline' || variant === 'ghost') {
    return (
      <TouchableOpacity
        style={[
          buttonStyles,
          variant === 'outline' && styles.outlineButton,
          {paddingVertical: sizeStyles.padding},
        ]}
        onPress={onPress}
        disabled={disabled || loading}>
        <View style={styles.content}>
          {loading ? (
            <ActivityIndicator color={colors.primary} />
          ) : (
            <>
              {icon && iconPosition === 'left' && (
                <Icon name={icon} size={20} color={colors.primary} style={styles.iconLeft} />
              )}
              <Text
                style={[
                  variant === 'outline' ? styles.outlineText : styles.ghostText,
                  {fontSize: sizeStyles.fontSize},
                ]}>
                {title}
              </Text>
              {icon && iconPosition === 'right' && (
                <Icon name={icon} size={20} color={colors.primary} style={styles.iconRight} />
              )}
            </>
          )}
        </View>
      </TouchableOpacity>
    );
  }

  return (
    <TouchableOpacity
      style={buttonStyles}
      onPress={onPress}
      disabled={disabled || loading}>
      <LinearGradient
        colors={getGradientColors()}
        style={[styles.gradient, {paddingVertical: sizeStyles.padding}]}
        start={{x: 0, y: 0}}
        end={{x: 1, y: 0}}>
        {loading ? (
          <ActivityIndicator color={colors.white} />
        ) : (
          <View style={styles.content}>
            {icon && iconPosition === 'left' && (
              <Icon name={icon} size={20} color={colors.white} style={styles.iconLeft} />
            )}
            <Text style={[styles.text, {fontSize: sizeStyles.fontSize}]}>{title}</Text>
            {icon && iconPosition === 'right' && (
              <Icon name={icon} size={20} color={colors.white} style={styles.iconRight} />
            )}
          </View>
        )}
      </LinearGradient>
    </TouchableOpacity>
  );
};

const styles = StyleSheet.create({
  button: {
    borderRadius: borderRadius.md,
    overflow: 'hidden',
    ...shadows.sm,
  },
  fullWidth: {
    width: '100%',
  },
  disabled: {
    opacity: 0.5,
  },
  gradient: {
    paddingHorizontal: spacing.base,
  },
  content: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
  },
  text: {
    fontWeight: '600',
    color: colors.white,
  },
  outlineButton: {
    borderWidth: 2,
    borderColor: colors.primary,
    paddingHorizontal: spacing.base,
  },
  outlineText: {
    fontWeight: '600',
    color: colors.primary,
  },
  ghostText: {
    fontWeight: '600',
    color: colors.primary,
  },
  iconLeft: {
    marginRight: spacing.xs,
  },
  iconRight: {
    marginLeft: spacing.xs,
  },
});

export default Button;
