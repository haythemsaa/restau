/**
 * Reusable Input Component
 */

import React, {useState} from 'react';
import {View, TextInput, Text, TouchableOpacity, StyleSheet} from 'react-native';
import Icon from 'react-native-vector-icons/Ionicons';
import {colors, typography, spacing, borderRadius} from '../utils/theme';

const Input = ({
  label,
  placeholder,
  value,
  onChangeText,
  icon,
  error,
  hint,
  secureTextEntry = false,
  keyboardType = 'default',
  autoCapitalize = 'sentences',
  multiline = false,
  numberOfLines = 1,
  editable = true,
  rightIcon,
  onRightIconPress,
  style,
  inputStyle,
}) => {
  const [isFocused, setIsFocused] = useState(false);
  const [showPassword, setShowPassword] = useState(false);

  const containerStyles = [
    styles.container,
    isFocused && styles.containerFocused,
    error && styles.containerError,
    !editable && styles.containerDisabled,
    style,
  ];

  return (
    <View style={styles.wrapper}>
      {label && <Text style={styles.label}>{label}</Text>}

      <View style={containerStyles}>
        {icon && (
          <Icon
            name={icon}
            size={20}
            color={isFocused ? colors.primary : colors.gray}
            style={styles.icon}
          />
        )}

        <TextInput
          style={[
            styles.input,
            multiline && styles.inputMultiline,
            inputStyle,
          ]}
          placeholder={placeholder}
          placeholderTextColor={colors.grayLight}
          value={value}
          onChangeText={onChangeText}
          secureTextEntry={secureTextEntry && !showPassword}
          keyboardType={keyboardType}
          autoCapitalize={autoCapitalize}
          multiline={multiline}
          numberOfLines={numberOfLines}
          editable={editable}
          onFocus={() => setIsFocused(true)}
          onBlur={() => setIsFocused(false)}
          textAlignVertical={multiline ? 'top' : 'center'}
        />

        {secureTextEntry && (
          <TouchableOpacity
            onPress={() => setShowPassword(!showPassword)}
            style={styles.rightIcon}>
            <Icon
              name={showPassword ? 'eye-outline' : 'eye-off-outline'}
              size={20}
              color={colors.gray}
            />
          </TouchableOpacity>
        )}

        {!secureTextEntry && rightIcon && (
          <TouchableOpacity onPress={onRightIconPress} style={styles.rightIcon}>
            <Icon name={rightIcon} size={20} color={colors.gray} />
          </TouchableOpacity>
        )}
      </View>

      {error && (
        <View style={styles.errorContainer}>
          <Icon name="alert-circle" size={14} color={colors.danger} />
          <Text style={styles.errorText}>{error}</Text>
        </View>
      )}

      {hint && !error && <Text style={styles.hint}>{hint}</Text>}
    </View>
  );
};

const styles = StyleSheet.create({
  wrapper: {
    marginBottom: spacing.base,
  },
  label: {
    fontSize: typography.fontSize.sm,
    fontWeight: '600',
    color: colors.text,
    marginBottom: spacing.xs,
  },
  container: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: colors.backgroundLight,
    borderRadius: borderRadius.md,
    borderWidth: 1,
    borderColor: colors.border,
    paddingHorizontal: spacing.base,
  },
  containerFocused: {
    borderColor: colors.primary,
    backgroundColor: colors.white,
  },
  containerError: {
    borderColor: colors.danger,
  },
  containerDisabled: {
    opacity: 0.6,
    backgroundColor: colors.borderLight,
  },
  icon: {
    marginRight: spacing.sm,
  },
  input: {
    flex: 1,
    height: 50,
    fontSize: typography.fontSize.base,
    color: colors.text,
  },
  inputMultiline: {
    height: 100,
    paddingVertical: spacing.sm,
  },
  rightIcon: {
    padding: spacing.xs,
    marginLeft: spacing.xs,
  },
  errorContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 4,
    gap: 4,
  },
  errorText: {
    fontSize: typography.fontSize.xs,
    color: colors.danger,
  },
  hint: {
    fontSize: typography.fontSize.xs,
    color: colors.textLight,
    marginTop: 4,
  },
});

export default Input;
